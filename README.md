## Symfony Components

This is a test project to test some symfony components. The project includes following components:

- [symfony/workflow](https://symfony.com/doc/current/components/workflow.html)
- [symfony/console](https://symfony.com/doc/current/components/console.html)
- [doctrine/collections](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/index.html)
- [symfony/var-dumper](https://symfony.com/doc/current/components/var_dumper.html)

### Installation

Clone the project and run `composer install` from inside the project directory. You may want to give execute permission
to the binary `workflow` by using `chmod +x ./workflow`. Use `sudo` if necessary.

### Overview

- #### Symfony Workflow
  The workflow component allows us to create finite state machines which can be used to create steps in doing certain
  tasks. In this example, I have used an order for example.

  An order has multiple states throughout its lifecycle. But, the states may only change bound by certain rules. The
  workflow component allows us to set the set of transition rules bound by which the finite state machine operates.

  The example of this finite state machine can be seen in `src/Workflows/Order/OrderWorkflow.php` and the internal
  mechanisms can be seen inside `src/Workflows/AbstractWorkflow.php`

- ### Symfony Console
  The whole application is a symfony console application. It allows us to create commands that perform specific tasks.
  In this example, the commands perform CRUD operation on some data and persist them on `storage/database.json` file.
  
  The starting point of this application is the `workflow` executable. The commands are scoped under `src/Commands/Order` directory.

- ### Doctrine Collections
  This component is used to create collections of data objects.
  It has features which allow us to perform certain operations like filtering, sorting etc that makes it more usable than traditional arrays.
  
  Its usage can be found inside `src/Collections directory.

- ### Var Dumper
  This is a debug helper component which helps us visualize data in a more user-friendly way.
  It works with both web and console like a charm.