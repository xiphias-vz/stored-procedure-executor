<?php


declare(strict_types=1);

namespace Xiphias\Zed\DatabaseTransaction\Business\Model;

use Generated\Shared\Transfer\ProcedureConfigurationTransfer;

class SqlStatement implements SqlStatementInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return string
     */
    public function build(ProcedureConfigurationTransfer $procedureConfigurationTransfer): string
    {
        if (!empty($procedureConfigurationTransfer->getParameters())) {
            $parameters = implode(',', $procedureConfigurationTransfer->getParameters());

            $statement = 'call  ' . $procedureConfigurationTransfer->getProcedureName() . '(' . $parameters . ')';
        } else {
            $statement = 'call  ' . $procedureConfigurationTransfer->getProcedureName() . '()';
        }

        return $statement;
    }
}
