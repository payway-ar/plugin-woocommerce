<?php
/**
 * Copyright © IURCO and PRISMA. All rights reserved.
 */

/**
 * Validate order cybersource interface
 *
 * Class WC_Decidir_Cybersource_Validator_Interface
 * @see https://decidirv2.api-docs.io/1.0/prevencion-de-fraude-by-cybersource/flujo-de-una-transaccion-con-cybersource
 */
interface WC_Decidir_Cybersource_Validator_Interface
{
    /**
     * @var string
     */
    const DECISION_GREEN = 'green';

    /**
     * @var string
     */
    const DECISION_RED = 'red';

    /**
     * @var string
     */
    const DECISION_YELLOW = 'yellow';

    /**
     * @var string
     */
    const DECISION_BLUE = 'BLUE';

    /**
     * @var string
     */
    const DECISION_BLACK = 'black';

    /**
     * @var string
     */
    const CYBERSOURCE_ERROR = 'cybersource_error';
}
