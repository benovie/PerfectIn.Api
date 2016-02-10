<?php
namespace PerfectIn\Api\Controller;

use TYPO3\Flow\Annotations as Flow;

/**
 * handle rest webservices
 *
 * @Flow\Scope("singleton")
 */
class RestController extends \TYPO3\Flow\Mvc\Controller\ActionController {	
	
	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 * handle webservice
	 * 
	 * @return void
	 */
	public function handleAction() { 
		$class 		= $this->request->getArgument('class');	
		$method 	= $this->request->getArgument('method');
		
		try {
			$call =  new \PerfectIn\Api\Webservice\WebserviceCall($class, $method);
			$call->setArgs($this->getArguments($class, $method));		
			if (!$call->isValid()) {
				$exception = new Exception\ValidationException('ValidationFailed', 1392379627);
				$exception->setValidationResults($call->getValidationResult());
				throw $exception;
			}
		
			$result = $call->invoke();

			$this->response->setHeader('Content-type','application/json');
			$this->response->setContent(json_encode($result));
		} catch(\Exception $exception) {
			$this->handleException($exception);
		}
	}
	
	/**
	 * handle exceptions
	 * 
	 * @param \Exception $exception
	 */
	protected function handleException($exception) {
		$exceptionResponse = new \stdClass();
		if (!$message = $exception->getMessage()) {
			$message = substr(get_class($exception), strrpos(get_class($exception), '\\') + 1, -9);
		}
		$exceptionResponse->message = $message;
		$exceptionResponse->code = $exception->getCode();
		$this->response->setStatus(400);
		$this->response->setHeader('Content-type','application/json');
		$this->response->setContent(json_encode($exceptionResponse));
	}
	
	/**
	 * get indexed arguments for method
	 * 
	 * @param string $class
	 * @param string $method
	 * @return array
	 */
	protected function getArguments($class, $method) {
		$arguments = array();		
		$parameters = $this->reflectionService->getMethodParameters($class, $method);
		
		foreach ($parameters AS $name => $parameter) {
			$arguments[$parameter['position']] = $this->request->hasArgument($name) ? $this->request->getArgument($name) : null;
		}
		
		return $arguments;
	}
	
	/**
	 * don't use views for this controller
	 * 
	 * @see TYPO3\Flow\Mvc\Controller.ActionController::resolveView()
	 */
	protected function resolveView() {
		return null;
	}
	
}

?>