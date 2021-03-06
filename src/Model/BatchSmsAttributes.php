<?php

namespace AliyunMNS\Model;

use AliyunMNS\Constants;
use AliyunMNS\Exception\MnsException;

/**
 * Please refer to
 * https://help.aliyun.com/document_detail/44501.html
 * for more details
 */
class BatchSmsAttributes
{
    public $freeSignName;
    public $templateCode;
    public $smsParams;

    public function __construct(
        $freeSignName, $templateCode, $smsParams = null)
    {
        $this->freeSignName = $freeSignName;
        $this->templateCode = $templateCode;
        $this->smsParams = $smsParams;
    }

    public function setFreeSignName($freeSignName)
    {
        $this->freeSignName = $freeSignName;
    }

    public function getFreeSignName()
    {
        return $this->freeSignName;
    }

    public function setTemplateCode($templateCode)
    {
        $this->templateCode = $templateCode;
    }

    public function getTemplateCode()
    {
        return $this->templateCode;
    }

    public function addReceiver($phone, $params)
    {
        if (!is_array($params)) {
            throw new MnsException(400, 'Params Should be Array!');
        }

        if (null == $this->smsParams) {
            $this->smsParams = [];
        }

        $this->smsParams[$phone] = $params;
    }

    public function getSmsParams()
    {
        return $this->smsParams;
    }

    public function writeXML(\XMLWriter $xmlWriter)
    {
        $jsonArray = ['Type' => 'multiContent'];
        if (null !== $this->freeSignName) {
            $jsonArray[Constants::FREE_SIGN_NAME] = $this->freeSignName;
        }
        if (null !== $this->templateCode) {
            $jsonArray[Constants::TEMPLATE_CODE] = $this->templateCode;
        }

        if (null != $this->smsParams) {
            if (!is_array($this->smsParams)) {
                throw new MnsException(400, 'SmsParams should be an array!');
            }
            if (!empty($this->smsParams)) {
                $jsonArray[Constants::SMS_PARAMS] = json_encode($this->smsParams, JSON_FORCE_OBJECT);
            }
        }

        if (!empty($jsonArray)) {
            $xmlWriter->writeElement(Constants::DIRECT_SMS, json_encode($jsonArray));
        }
    }
}
