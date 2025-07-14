<?php
namespace XiphiasTest\Zed\DatabaseTransaction\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProcedureConfigurationTransfer;
use PDO;
use PDOStatement;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Connection\ConnectionWrapper;
use Propel\Runtime\Connection\ConnectionInterface;
use Xiphias\Zed\DatabaseTransaction\Persistence\Transaction;
use Xiphias\Zed\DatabaseTransaction\Business\Model\SqlStatementInterface;

class TransactionTest extends Unit
{
    public function testExecuteProcedureWithPayloadCommitsOnSuccess(): void
    {
        $procedureConfig = $this->createMock(ProcedureConfigurationTransfer::class);

        $sql = 'SELECT * FROM dummy_procedure()';

        $sqlStatementMock = $this->createMock(SqlStatementInterface::class);
        $sqlStatementMock->method('build')->willReturn($sql);

        $pdoStatementMock = $this->createMock(PDOStatement::class);
        $pdoStatementMock->expects($this->once())->method('execute');
        $pdoStatementMock->method('fetchAll')->willReturn([
            ['some' => 'data']
        ]);

        $connectionMock = $this->createMock(ConnectionInterface::class);
        $connectionMock->expects($this->once())->method('beginTransaction');
        $connectionMock->expects($this->once())->method('commit');
        $connectionMock->expects($this->never())->method('rollBack');
        $connectionMock->expects($this->once())->method('prepare')->with($sql)->willReturn($pdoStatementMock);

        $serviceContainer = new StandardServiceContainer();
        $serviceContainer->setConnection('default', $connectionMock);

        $serviceContainer->setAdapterClass('default', '\Propel\Runtime\Adapter\Pdo\MysqlAdapter');

        Propel::setServiceContainer($serviceContainer);

        $transaction = new Transaction($sqlStatementMock);
        $result = $transaction->executeProcedureWithPayload($procedureConfig);

        $this->assertEquals([['some' => 'data']], $result);
    }

    public function testExecuteProcedureWithPayloadThrowsExceptionOnErrorResult(): void
    {
        $procedureConfig = $this->createMock(ProcedureConfigurationTransfer::class);

        $sql = 'SELECT * FROM dummy_procedure()';

        $sqlStatementMock = $this->createMock(SqlStatementInterface::class);
        $sqlStatementMock->method('build')->willReturn($sql);

        $pdoStatementMock = $this->createMock(PDOStatement::class);
        $pdoStatementMock->expects($this->once())->method('execute');
        $pdoStatementMock->method('fetchAll')->willReturn([
            [
                'Level' => 'ERROR',
                'Code' => '1234',
                'Message' => 'Something went wrong'
            ]
        ]);

        $connectionMock = $this->createMock(ConnectionInterface::class);
        $connectionMock->expects($this->once())->method('beginTransaction');
        $connectionMock->expects($this->never())->method('commit');
        $connectionMock->expects($this->once())->method('rollBack');
        $connectionMock->expects($this->once())->method('prepare')->with($sql)->willReturn($pdoStatementMock);

        $serviceContainer = new StandardServiceContainer();

        $serviceContainer->setAdapterClass('default', \Propel\Runtime\Adapter\Pdo\PgsqlAdapter::class);
        $serviceContainer->setConnection('default', $connectionMock);
        Propel::setServiceContainer($serviceContainer);

        $transaction = new \Xiphias\Zed\DatabaseTransaction\Persistence\Transaction($sqlStatementMock);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('ERROR: 1234 Something went wrong');

        $transaction->executeProcedureWithPayload($procedureConfig);
    }


    public function testExecuteProcedureWithoutPayloadCommitsOnSuccess(): void
    {
        $procedureConfig = $this->createMock(ProcedureConfigurationTransfer::class);
        $sql = 'UPDATE dummy_procedure()';

        $sqlStatementMock = $this->createMock(SqlStatementInterface::class);
        $sqlStatementMock->method('build')->willReturn($sql);

        $pdoStatementMock = $this->createMock(PDOStatement::class);
        $pdoStatementMock->expects($this->once())->method('execute');

        $connectionMock = $this->createMock(ConnectionInterface::class);
        $connectionMock->expects($this->once())->method('beginTransaction');
        $connectionMock->expects($this->once())->method('commit');
        $connectionMock->expects($this->never())->method('rollBack');
        $connectionMock->expects($this->once())->method('prepare')->with($sql)->willReturn($pdoStatementMock);

        $serviceContainer = new StandardServiceContainer();
        $serviceContainer->setAdapterClass('default', \Propel\Runtime\Adapter\Pdo\PgsqlAdapter::class);
        $serviceContainer->setConnection('default', $connectionMock);
        Propel::setServiceContainer($serviceContainer);

        $transaction = new Transaction($sqlStatementMock);
        $transaction->executeProcedureWithoutPayload($procedureConfig);
    }

    public function testExecuteProcedureWithoutPayloadRollsBackOnException(): void
    {
        $procedureConfig = $this->createMock(ProcedureConfigurationTransfer::class);
        $sql = 'UPDATE dummy_procedure()';

        $sqlStatementMock = $this->createMock(SqlStatementInterface::class);
        $sqlStatementMock->method('build')->willReturn($sql);

        $connectionMock = $this->createMock(ConnectionInterface::class);
        $connectionMock->expects($this->once())->method('beginTransaction');
        $connectionMock->expects($this->never())->method('commit');
        $connectionMock->expects($this->once())->method('rollBack');
        $connectionMock->expects($this->once())->method('prepare')->with($sql)->willThrowException(new \Exception('DB failure'));

        $serviceContainer = new StandardServiceContainer();
        $serviceContainer->setAdapterClass('default', \Propel\Runtime\Adapter\Pdo\PgsqlAdapter::class);
        $serviceContainer->setConnection('default', $connectionMock);
        Propel::setServiceContainer($serviceContainer);

        $transaction = new Transaction($sqlStatementMock);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DB failure');

        $transaction->executeProcedureWithoutPayload($procedureConfig);
    }

    public function testBuildReturnsSqlWithParameters(): void
    {
        $procedureName = 'my_procedure';
        $parameters = ['1', "'param2'", '3.14'];

        $procedureConfigMock = $this->createMock(ProcedureConfigurationTransfer::class);
        $procedureConfigMock->method('getProcedureName')->willReturn($procedureName);
        $procedureConfigMock->method('getParameters')->willReturn($parameters);

        $sqlStatement = new \Xiphias\Zed\DatabaseTransaction\Business\Model\SqlStatement();
        $result = $sqlStatement->build($procedureConfigMock);

        $this->assertEquals("call  my_procedure(1,'param2',3.14)", $result);
    }

    public function testBuildReturnsSqlWithoutParameters(): void
    {
        $procedureName = 'my_procedure';

        $procedureConfigMock = $this->createMock(ProcedureConfigurationTransfer::class);
        $procedureConfigMock->method('getProcedureName')->willReturn($procedureName);
        $procedureConfigMock->method('getParameters')->willReturn([]);

        $sqlStatement = new \Xiphias\Zed\DatabaseTransaction\Business\Model\SqlStatement();
        $result = $sqlStatement->build($procedureConfigMock);

        $this->assertEquals("call  my_procedure()", $result);
    }

}
