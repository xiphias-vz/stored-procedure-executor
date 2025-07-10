<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Xiphias\Zed\DatabaseTransaction\Persistence;

use Generated\Shared\Transfer\ProcedureConfigurationTransfer;

interface TransactionInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return array
     */
    public function executeProcedureWithPayload(ProcedureConfigurationTransfer $procedureConfigurationTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return void
     */
    public function executeProcedureWithoutPayload(ProcedureConfigurationTransfer $procedureConfigurationTransfer): void;
}
