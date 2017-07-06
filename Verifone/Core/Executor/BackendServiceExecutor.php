<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 * @author     Szymon Nosal <simon@lamia.fi>
 *
 */

namespace Verifone\Core\Executor;

use Lamia\Validation\Exception\FieldValidationFailedException;
use Lamia\Validation\Validation\Interfaces\Validation;
use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\Converter\Response\ResponseConverter;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;
use Verifone\Core\DependencyInjection\Utils\Cutter;
use Verifone\Core\DependencyInjection\Validation\CommonValidation;
use Verifone\Core\DependencyInjection\Validation\Response\ResponseValidation;
use Verifone\Core\Exception\ResponseCheckFailedException;
use Verifone\Core\Service\Backend\BackendService;
use Verifone\Core\Transport\Transport;

/**
 * Class BackendServiceExecutor
 * @package Verifone\Core
 * Executes the service with given transport and verifies result with given public key
 */
class BackendServiceExecutor
{

    private $transport;
    private $cryptUtil;
    private $validation;
    private $converter;
    private $cutter;
    private $serviceResponseConverter;

    /**
     * BackendServiceExecutor constructor.
     * @param CommonValidation $validation
     * @param CryptUtil $cryptUtil
     * @param Transport $transport
     * @param ResponseConverter $converter
     * @param Cutter $cutter
     */
    public function __construct(
        CommonValidation $validation,
        CryptUtil $cryptUtil,
        Transport $transport,
        ResponseConverter $converter,
        Cutter $cutter
    ) {
        $this->transport = $transport;
        $this->cryptUtil = $cryptUtil;
        $this->validation = $validation;
        $this->converter = $converter;
        $this->cutter = $cutter;
    }

    /**
     * @param BackendService $service to be executed
     * @param string $publicKey for verification
     * @return array of response fields
     * @throws ResponseCheckFailedException if something was wrong with response
     * @throws FieldValidationFailedException
     */
    public function executeService(BackendService $service, $publicKey)
    {
        $this->serviceResponseConverter = $service->getResponseConverter();
        $urls = $service->getUrls();
        $requestFields = $service->getFields()->getAsArray();
        $requestFields = $this->cutter->cutFields($requestFields);
        $this->validation->validate($requestFields);
        if (!is_array($urls)) {
            throw new FieldValidationFailedException('urls', 'should be array');
        }

        foreach ($urls as $url) {
            $response = $this->transport->post($url, $requestFields);
            if ($response instanceof TransportationResponse) {
                return $this->validateAndFormatResponse(
                    $response,
                    $requestFields,
                    $publicKey,
                    $service->getMatchingFields()
                );
            }
        }
        throw new ResponseCheckFailedException('None of the urls returned 200 ok -response');
    }

    /**
     * Formats response fields to an array and validates that everything is correct
     * @param TransportationResponse $response containing response fields
     * @param array $requestFields
     * @param string $publicKey to verify response signature
     * @param array $matchingFields
     * @return array of response fields
     * @throws ResponseCheckFailedException if validation of response failed
     */
    private function validateAndFormatResponse(
        TransportationResponse $response,
        array $requestFields,
        $publicKey,
        array $matchingFields
    ) {
        $responseFields = $this->converter->convert($response);
        $this->validation->validateResponse($requestFields, $responseFields, $publicKey, $matchingFields);
        return $this->serviceResponseConverter->convert(new CoreResponse(0, $responseFields));
    }
}
