<?php


declare(strict_types=1);

namespace Xiphias\Zed\DatabaseTransaction\Business;

use Generated\Shared\Transfer\ProcedureConfigurationTransfer;

interface DatabaseTransactionFacadeInterface
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
