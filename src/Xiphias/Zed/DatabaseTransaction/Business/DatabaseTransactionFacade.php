<?php


declare(strict_types=1);

namespace Xiphias\Zed\DatabaseTransaction\Business;

use Generated\Shared\Transfer\ProcedureConfigurationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Xiphias\Zed\DatabaseTransaction\Business\DatabaseTransactionBusinessFactory getFactory()
 */
class DatabaseTransactionFacade extends AbstractFacade implements DatabaseTransactionFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProcedureConfigurationTransfer $procedureConfigurationTransfer
     *
     * @return array<int, array<string, mixed>>
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
