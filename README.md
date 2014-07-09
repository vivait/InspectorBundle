InspectorBundle
===============


## Registering a new condition entity
To register a new condition, the condition must extend ```Vivait\InspectorBundle\Entity\Condition```. 

### getFormType
The ```Condition``` class will require that the ```getFormType``` method is declared. This should be a simple method providing either the name of
the form or an instance of the form type for that entity.

### loadService
It will also require that the ```loadService``` method is declared. The bundle will provide this method with the service
container and this method will be responsible for loading and returning the service that will perform the condition.

For simple conditions that implement the ```ConditionInterface```, the ```loadService``` method can return ```$this``` (although this is not recommend as it violates the [SRP](http://www.sitepoint.com/the-single-responsibility-principle/).

For more complicated logic, or logic that requires external services, it is necessary to extra this functionality in to a
separate class.