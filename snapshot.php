<?php

  /*
  * Name: Snapshot
  * @description: Snaps a preview of a whatever site is passed
  * @author: Joshua Grierson
  * @version: 1.0
  */

  class Snapshot {

    /*
    * @var url
    */
    protected $_url;

    /*
    * @var headers
    */
    protected $_headers;

    /*
    * @var timeout
    */
    protected $_timeout;

    /*
    * @var status
    */
    protected $_status;

    /*
    * @var response
    */
    protected $_response;

    /*
    * @var snapshot
    */
    protected $_snapshot;

    /*
    * @var imageRegex
    */
    private $_imageRegex = '/<img\s+src="(.*?)"/i';

    /*
    * @var titleRegex
    */
    private $_titleRegex = '/<title>(.*?)<\/title>/i';

    /*
    * @var domainRegex
    */
    private $_domainRegex = '/(http|https)\:\/\/(.*?)\//i';

    /*
    * @var descriptionRegex
    */
    private $_descriptionRegex = '/<meta\s+name="description"\s+content="(.*?)"\>/i';

    private function __construct ($url) {

      $this->_url = $url;
      $this->_headers = [
        'Content-Type' => 'text/html; charset=utf-8'
      ];
      $this->_timeout = 40;

    }

    /*
    * @function: build
    * @declaration: public
    */
    public function build () {

      try {

        if(!$this->_url) {
          throw new Exception('URL not provided');
        }

        $this->curlit();
        $this->snapsite();

      } catch (Exception $ex) {

        $this->_snapshot['error'] = $ex->getMessage();

      }

      return $this;

    }

    /*
    * @function: getSnapshot
    * @declaration: public
    */
    public function getSnapshot () {
      return json_encode($this->_snapshot);
    }

    /*
    * @function: snapsite
    * @declaration: protected
    */
    protected function snapsite () {

      if(!$this->_response) {
        throw new Exception('Response was null');
      }

      // snap favicon
      $this->_snapshot['favicon'] = '';
      $this->_snapshot['favicon'] = $this->get_favicon();

      // snap title
      $this->_snapshot['title'] = '';
      $this->_snapshot['title'] = $this->get_title();

      // snap description
      $this->_snapshot['description'] = '';
      $this->_snapshot['description'] = $this->get_description();

      // snap image
      $this->_snapshot['image'] = '';
      $this->_snapshot['image'] = $this->get_image();

      // snap domain
      $this->_snapshot['domain'] = (
        $this->get_domain() ? $this->get_domain() : ''
      );
      $this->_snapshot['siteUrl'] = $this->_url;

    }

    /*
    * @function: curlit
    * @declaration: protected
    */
    protected function curlit () {

      $curl = curl_init();

      curl_setopt($curl, CURLOPT_URL, $this->_url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_headers);
      curl_setopt($curl, CURLOPT_TIMEOUT, $this->_timeout);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      if($this->is_ssl($this->_url)) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      }

      $this->_response = curl_exec($curl);
      $this->_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    }

    /*
    * @function: get_description
    * @declaration: protected
    */
    protected function get_description () {

      preg_match($this->_descriptionRegex, $this->_response, $descriptionMatches);
      if(!empty($descriptionMatches) && isset($descriptionMatches[1])) {

        return $descriptionMatches[1];

      }

      return null;

    }

    /*
    * @function: get_title
    * @declaration: protected
    */
    protected function get_title () {

      preg_match($this->_titleRegex, $this->_response, $titleMatches);
      if(!empty($titleMatches) && isset($titleMatches[1])) {

        return $titleMatches[1];

      }

      return null;

    }

    /*
    * @function: get_favicon
    * @declaration: protected
    */
    protected function get_favicon () {

      $favicon = @file_get_contents(
        ($this->_url.'/favicon.ico'), false, stream_context_create([
          'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
          ]
        ])
      );
      if($favicon) {
        return ('data:image/x-icon;base64,'.base64_encode($favicon));
      }

      return null;

    }

    /*
    * @function: get_image
    * @declaration: protected
    */
    protected function get_image () {

      preg_match($this->_imageRegex, $this->_response, $imageMatches);
      if(!empty($imageMatches) && isset($imageMatches[1])) {

        if(substr($imageMatches[1], 0, 4) == 'http') {
          return $imageMatches[1];
        } else {
          return $this->_url.$imageMatches[1];
        }

      }

      return null;

    }

    /*
    * @function: get_domain
    * @declaration: protected
    */
    protected function get_domain () {

      preg_match($this->_domainRegex, $this->_url, $domainMatches);
      if(!empty($domainMatches) && isset($domainMatches[2])) {

        return str_replace('www.', '', $domainMatches[2]);

      }

      return null;

    }

    /*
    * @function: is_ssl
    * @declaration: protected
    */
    protected function is_ssl ($url) {

      if(substr($url, 0, 5) == 'https') {
        return true;
      }

      return false;

    }

    /*
    * @function: getCurlStatus
    * @declaration: public
    */
    public function getCurlStatus () {
      return $this->_status;
    }

    /*
    * @importance: Main
    * @function: forge
    * @declaration: protected
    */
    public static function forge ($url) {
      return new static($url);
    }

  }

?>
