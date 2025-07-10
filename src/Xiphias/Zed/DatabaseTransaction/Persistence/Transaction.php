<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Xiphias\Zed\DatabaseTransaction\Persistence;

use Exception;
use Generated\Shared\Transfer\ProcedureConfigurationTransfer;
use PDO;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Propel;
use Xiphias\Zed\DatabaseTransaction\Business\Model\SqlStatementInterface;

class Transaction implements TransactionInterface
{
    protected SqlStatementInterface $sqlStatement;

    /**
     * @var string
     */
    protected const ERROR = 'Error';

    /**
     * @var string
     */
    protected const ERROR_CODE = 'Code';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Message';

    /**
     * @var string
     */
    protected const EXCEPTION_LEVEL = 'Level';

    /**
     * @param \Xiphias\Zed\SqlStatementInterface $sqlStatement
     */
    public function __construct(SqlStatementInterface $sqlStatement)
    {
        $this->sqlStatement = $sqlStatement;
    }

    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @throws \Exception
     *
     * @return array
     */
    public function executeProcedureWithPayload(ProcedureConfigurationTransfer $procedureConfigurationTransfer): array
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();
        try {
            $sqlStatement = $this->sqlStatement->build($procedureConfigurationTransfer);
            $result = $this->executeSqlCommand($sqlStatement, $connection);

            if (isset(current($result)[static::EXCEPTION_LEVEL])) {
                $message = current($result)[static::EXCEPTION_LEVEL] . ': ' .
                    current($result)[static::ERROR_CODE] . ' ' .
                    current($result)[static::ERROR_MESSAGE];

                throw new Exception($message);
            }

            $connection->commit();

            return $result;
        } catch (Exception $e) {
            $connection->rollBack();

            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @throws \Exception
     *
     * @return void
     */
    public function executeProcedureWithoutPayload(ProcedureConfigurationTransfer $procedureConfigurationTransfer): void
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        try {
            $sqlStatement = $this->sqlStatement->build($procedureConfigurationTransfer);
            $this->executeUpdateSqlCommand($sqlStatement, $connection);
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();

            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $sqlStatement
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return array
     */
    public function executeSqlCommand(string $sqlStatement, ConnectionInterface $connection): array
    {
        $statement = $connection->prepare($sqlStatement);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_NAMED);
    }

    /**
     * @param string $sqlStatement
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    public function executeUpdateSqlCommand(string $sqlStatement, ConnectionInterface $connection): void
    {
        $statement = $connection->prepare($sqlStatement);
        $statement->execute();
    }
}
