<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
