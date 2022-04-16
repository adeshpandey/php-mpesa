<?php

namespace Adeshpandey\PhpMpesa;

class PhpMpesa
{
    private $_mPesaHost;
    private $_mPesaClient;
    private $_sessionID;
    private $_namespace;

    function __construct($url, $namespace)
    {
        $this->_mPesaHost = $url;
        $this->_namespace = $namespace;
    }
    function connect($url = null)
    {
        if ($url) {
            $this->_mPesaHost = $url;
        }

        if ($this->_mPesaHost) {
            $this->_mPesaClient = new \SoapClient($this->_mPesaHost);
        } else {
            throw new \Exception("invalid URL");
        }
        return $this;
    }

    function getSessionID()
    {
        return $this->_sessionID;
    }

    function getClient()
    {
        return $this->_sessionID;
    }

    function getHost()
    {
        return $this->_sessionID;
    }

    function login($username, $password, $event_id)
    {
        $this->_mPesaClient->__setSoapHeaders(new \SoapHeader($this->_namespace, "EventID", $event_id));

        $q = sprintf("<dataItem>
    <name>Username</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>Password</name>
    <type>String</type>
    <value>%s</value>
</dataItem>", $username, $password);

        $r = $this->_mPesaClient->getGenericResult([new \SoapVar($q, XSD_ANYXML)]);
        $this->_sessionID = $r->response->dataItem->value;

        return $this;
    }

    public function b2c($event_id, $token, $isdn, $amt, $ref_id, $appName, $cbUrl, $cmd_id)
    {
        $d = new \DateTime();
        $this->_mPesaClient->__setSoapHeaders([new \SoapHeader($this->_namespace, "EventID", $event_id), new \SoapHeader($this->_namespace, "Token", $token)]);

        $req = sprintf("<dataItem>
    <name>ServiceProviderName</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>CustomerMSISDN</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>Currency</name>
    <type>String</type>
    <value>USD</value>
</dataItem>
<dataItem>
    <name>Amount</name>
    <type>String</type>
    <value>%d</value>
</dataItem>
<dataItem>
    <name>TransactionDateTime</name>
    <type>Date</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>Shortcode</name>
    <type>String</type>
    <value>15058</value>
</dataItem>
<dataItem>
    <name>Language</name>
    <type>String</type>
    <value>EN</value>
</dataItem>
<dataItem>
    <name>ThirdPartyReference</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>CallBackChannel</name>
    <type>String</type>
    <value>2</value>
</dataItem>
<dataItem>
    <name>CallBackDestination</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>CommandID</name>
    <type>String</type>
    <value>%s</value>
</dataItem>", $appName, $isdn, $amt, $d->getTimestamp(), $ref_id, $cbUrl, $cmd_id);

        $r = $this->_mPesaClient->getGenericResult([new \SoapVar($req, XSD_ANYXML)]);

        return $r;
    }

    public function c2b($event_id, $token, $msisdn, $provider_code, $currency, $amt, $ref_id, $cmd_id, $cb_url)
    {

        $d = new \DateTime();
        $this->_mPesaClient->__setSoapHeaders([new \SoapHeader($this->_namespace, "EventID", $event_id), new \SoapHeader($this->_namespace, "Token", $token)]);

        $req = sprintf("<dataItem>
    <name>CustomerMSISDN</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>ServiceProviderCode</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>Currency</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>Amount</name>
    <type>String</type>
    <value>%d</value>
</dataItem>
<dataItem>
    <name>Date</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>ThirdPartyReference</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>CommandId</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>Language</name>
    <type>String</type>
    <value>EN</value>
</dataItem>
<dataItem>
    <name>CallBackChannel</name>
    <type>String</type>
    <value>4</value>
</dataItem>
<dataItem>
    <name>CallBackDestination</name>
    <type>String</type>
    <value>%s</value>
</dataItem>
<dataItem>
    <name>Surname</name>
    <type>String</type>
    <value>Surname</value>
</dataItem>
<dataItem>
    <name>Initials</name>
    <type>String</type>
    <value>Initials</value>
</dataItem>", $msisdn, $provider_code, $currency, $amt, $d->getTimestamp(), $ref_id, $cmd_id, $cb_url);
        $r = $this->_mPesaClient->getGenericResult([new \SoapVar($req, XSD_ANYXML)]);

        return $r;
    }
}
