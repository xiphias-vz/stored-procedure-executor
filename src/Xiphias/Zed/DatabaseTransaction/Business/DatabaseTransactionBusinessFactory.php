<?php


declare(strict_types=1);

namespace Xiphias\Zed\DatabaseTransaction\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Xiphias\Zed\DatabaseTransaction\Business\Model\SqlStatement;
use Xiphias\Zed\DatabaseTransaction\Business\Model\SqlStatementInterface;
use Xiphias\Zed\DatabaseTransaction\Persistence\Transaction;
use Xiphias\Zed\DatabaseTransaction\Persistence\TransactionInterface;

class DatabaseTransactionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Xiphias\Zed\DatabaseTransaction\Persistence\TransactionInterface
     */
    public function createTransaction(): TransactionInterface
    {
        return new Transaction(
            $this->createSqlStatement(),
        );
    }

    /**
     * @return \Xiphias\Zed\DatabaseTransaction\Business\Model\SqlStatementInterface
     */
    public function createSqlStatement(): SqlStatementInterface
    {
        return new SqlStatement();
    }
}
