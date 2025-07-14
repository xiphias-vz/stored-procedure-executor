<?php


namespace Xiphias\Zed\DatabaseTransaction\Persistence;

use Generated\Shared\Transfer\ProcedureConfigurationTransfer;

interface TransactionInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return array<int, array<string, mixed>>
     */
    public function executeProcedureWithPayload(ProcedureConfigurationTransfer $procedureConfigurationTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return void
     */
    public function executeProcedureWithoutPayload(ProcedureConfigurationTransfer $procedureConfigurationTransfer): void;
}
