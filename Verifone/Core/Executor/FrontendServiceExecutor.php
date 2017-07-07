<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Executor;

use Verifone\Core\Converter\Request\RequestConverter;
use Verifone\Core\DependencyInjection\Utils\Cutter;
use Verifone\Core\DependencyInjection\Validation\CommonValidation;
use Verifone\Core\Exception\NoAvailableUrlException;
use Verifone\Core\Service\Frontend\FrontendService;
use Verifone\Core\Transport\Transport;

/**
 * Class FrontendServiceExecutor
 * @package Verifone\Core\Executor
 * The purpose of the class is to validate and convert verifone frontend request into needed format
 */
class FrontendServiceExecutor
{
    private $validation;
    private $converter;
    private $transport;
    private $cutter;

    /**
     * FrontendServiceExecutor constructor.
     * @param CommonValidation $validation
     * @param RequestConverter $converter
     * @param Transport $transport
     * @param Cutter $cutter
     */
    public function __construct(
        CommonValidation $validation,
        RequestConverter $converter,
        Transport $transport
    ) {
        $this->validation = $validation;
        $this->converter = $converter;
        $this->transport = $transport;
    }

    /**
     * @param FrontendService $service
     * @param array $actionUrls
     * @param bool $checkUrlAvailability whether to check url availability or not, defaults to false
     * @return mixed depending on what kind of conversion/converter is wanted for the request
     */
    public function executeService(FrontendService $service, array $actionUrls, $checkUrlAvailability = false)
    {
        $actionUrl = $this->resolveActionUrl($actionUrls, $checkUrlAvailability);
        $storage = $service->getFields();
        $this->validation->validate($storage->getAsArray());
        return $this->converter->convert($storage, $actionUrl);
    }

    private function resolveActionUrl(array $actionUrls, $checkUrlAvailability)
    {
        if ($checkUrlAvailability) {
            return $this->returnFirstAvailableUrl($actionUrls);
        }
        return current($actionUrls);
    }

    private function returnFirstAvailableUrl(array $urls)
    {
        foreach ($urls as $url) {
            if ($this->isAvailable($url)) {
                return $url;
            }
        }
        throw new NoAvailableUrlException('');
    }

    private function isAvailable($url)
    {
        try {
            $response = $this->transport->get($url);
            if ($response->getBody() == '') {
                return true;
            }
        } catch (\Exception $e) { }
        return false;
    }
}
