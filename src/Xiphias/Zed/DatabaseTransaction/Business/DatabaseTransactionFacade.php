<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Xiphias\Zed\DatabaseTransaction\Business;

use Generated\Shared\Transfer\ProcedureConfigurationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Xiphias\Zed\DatabaseTransaction\Business\DatabaseTransactionFacadeInterface;

/**
 * @method \Xiphias\Zed\DatabaseTransaction\Business\DatabaseTransactionBusinessFactory getFactory()
 */
class DatabaseTransactionFacade extends AbstractFacade implements DatabaseTransactionFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return array
     */
    public function executeProcedureWithPayload(ProcedureConfigurationTransfer $procedureConfigurationTransfer): array
    {
        return $this->getFactory()
            ->createTransaction()
            ->executeProcedureWithPayload($procedureConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return void
     */
    public function executeProcedureWithoutPayload(ProcedureConfigurationTransfer $procedureConfigurationTransfer): void
    {
        $this->getFactory()
            ->createTransaction()
            ->executeProcedureWithoutPayload($procedureConfigurationTransfer);
    }
}
