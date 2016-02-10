PerfectIn.Api
==============
 is a TYPO3.Flow package to create api's for existing code


## Example routing

### Find all TYPO3\Flow\Security\Role with REST

Routes.yaml:

```
-
  name: 'Rest route for Roles'
  uriPattern: role
  httpMethods:
    - GET
  defaults:
    '@package': PerfectIn.Api
    '@controller': Rest
    '@action': handle
    class: TYPO3\Flow\Security\Policy\PolicyService
    method: getRoles
 
```       
    

### Find one TYPO3\Flow\Security\Role with REST

> Note that the variable {roleIdentifier} in the url is automatically mapped to the $roleIdentifier parameter in the `getRole` method


Routes.yaml:

```
-
  name: 'Rest route for Role'
  uriPattern: 'role/{roleIdentifier}'
  httpMethods:
    - GET
  defaults:
    '@package': PerfectIn.Api
    '@controller': Rest
    '@action': handle
    class: TYPO3\Flow\Security\Policy\PolicyService
    method: getRole

```

