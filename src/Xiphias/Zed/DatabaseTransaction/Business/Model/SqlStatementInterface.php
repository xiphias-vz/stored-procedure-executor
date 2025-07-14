<?php


declare(strict_types=1);

namespace Xiphias\Zed\DatabaseTransaction\Business\Model;

use Generated\Shared\Transfer\ProcedureConfigurationTransfer;

interface SqlStatementInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return string
     */
    public function build(ProcedureConfigurationTransfer $procedureConfigurationTransfer): string;
}
