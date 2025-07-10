<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
     * @return \Xiphias\Zed\TransactionInterface
     */
    public function createTransaction(): TransactionInterface
    {
        return new Transaction(
            $this->createSqlStatement(),
        );
    }

    /**
     * @return \Xiphias\Zed\SqlStatementInterface
     */
    public function createSqlStatement(): SqlStatementInterface
    {
        return new SqlStatement();
    }
}
