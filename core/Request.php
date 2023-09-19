<?php
	
	namespace app\core;
	class Request
	{
		public array $params = [];
		
		function getPath ()
		{
			return Utility::parseURI($_SERVER['REQUEST_URI']);
		}
		
		function method ()
		{
			$this->isOtherRequestType();
			return \strtolower($_SERVER['REQUEST_METHOD']);
		}
		
		function isGet ()
		{
			return $this->method() === 'get';
		}
		
		function isPost ()
		{
			return $this->method() === 'post';
		}
		
		function isPut ()
		{
			return $this->method() === 'put';
		}
		
		function isDelete ()
		{
			return $this->method() === 'delete';
		}
		
		function isPatch ()
		{
			return $this->method() === 'patch';
		}
		
		function isOtherRequestType ()
		{
			// if input on body has name _method, set that as the method on the $_SERVER global array
			if (array_key_exists('_method', $_GET))
			{
				$_SERVER['REQUEST_METHOD'] = strtoupper(filter_input(INPUT_GET, '_method', FILTER_SANITIZE_SPECIAL_CHARS));
				return true;
			}
			if (array_key_exists('_method', $_POST))
			{
				$_SERVER['REQUEST_METHOD'] = strtoupper(filter_input(INPUT_POST, '_method', FILTER_SANITIZE_SPECIAL_CHARS));
				return true;
			}
			return false;
		}
		
		function getBody ()
		{
			$body = [];
			if ($this->method() == 'get')
			{
				foreach ($_GET as $key => $value)
				{
					$body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
				}
			} elseif ($this->method() == 'post')
			{
				foreach ($_POST as $key => $value)
				{
					$body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
				}
			} elseif ($this->method() != 'get' || $this->method() != 'post')
			{
				$filtered_get = [];
				$filtered_post = [];
				foreach ($_GET as $key => $value)
				{
					$filtered_get[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
				}
				foreach ($_POST as $key => $value)
				{
					$filtered_post[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
				}
				$body = array_merge($filtered_get, $filtered_post);
			}
			return $body;
		}
		
		/**
		 * Method to fetch parameters from the request URI and attach them to the named parameters in the route
		 * Parameters are defined using {[name]} eg {id}
		 *
		 * usage:
		 * /user/{id} ===> /user/5 the request object will hold a params array its an assoc array of key value pairs. like: ['id' => 5]
		 *
		 * @param array  $routesList Path of routes based on the used method must be passed via the router eg [get => [/user/{id} => callback]]
		 * @param string $uriPath    URI passed from the server is the /user/5
		 *
		 * @return string returns the route path for use within the router, or will return the original uri path passed for it to 404 fail
		 */
		function parameterSearch ($routesList, $uriPath)
		{
			$newRoutesList = [];
			// 2 filter routes to the same number of steps in the uri
			foreach ($routesList as $route => $callBack)
			{
				// if this is a direct path match then exit and return immediatly no need to continue through the whole process.
				if ($route === $uriPath)
				{
					return $uriPath;
				}
				$routePath = preg_replace("/(^\/)|(\/$)/", "", $route);
				$uri = preg_replace("/(^\/)|(\/$)/", "", $uriPath);
				if (count(explode('/', $routePath)) == count(explode('/', $uri)))
				{
					$newRoutesList[$route] = $routesList[$route];
				}
			}
			// 4.a check see if they are a direct match if they are that is the route to use
			// 4.b if not then check route string for '{}' in them
			// 4.c check with match should it work then extract params and run the route.
			foreach ($newRoutesList as $route => $callback)
			{
				preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);
				$uri = explode('/', preg_replace("/(^\/)|(\/$)/", "", $route));
				$uriPathArray = explode('/', preg_replace("/(^\/)|(\/$)/", "", $uriPath));
				$params = [];
				$indexes = [];
				$paramNames = [];
				if (count($paramMatches) > 0)
				{
					foreach ($paramMatches[0] as $key)
					{
						$paramNames[] = $key;
					}
				}
				$matchFailed = false;
				foreach ($uri as $index => $param)
				{
					if (preg_match("/{.*}/", $param))
					{
						$indexes[] = $index;
					} else
					{
						//see if uri path array has the same value, if not then its not a match and should be broken out of and to the next...
						if ($param === $uriPathArray[$index])
						{
							//matched so can continue
							continue;
						} else
						{
							$matchFailed = true;
							// didnt match so break out of this and then continue
							break;
						}
					}
				}
				if ($matchFailed === true)
				{
					// match wasnt found on that pass and soo moving to the next route in the list
					continue;
				}
				// match found so now we are rebuilding teh path and returning that route back for the
				foreach ($indexes as $key => $index)
				{
					// no no happened
					if (empty($uriPathArray[$index]))
					{
						return false;
					}
					$params[$paramNames[$key]] = $uriPathArray[$index];
				}
				//resetting parameters to teh request object
				$this->params = $params;
				return $route;
			}
			// possibly not found, maybe it wasnt caught else where and will likely throw a 404 error
			return $uriPath;
		}
		
	}