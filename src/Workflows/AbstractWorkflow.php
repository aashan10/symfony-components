<?php

namespace Aashan\Workflow\Workflows;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

abstract class AbstractWorkflow implements WorkflowInterface
{
    protected array $places;
    protected array $transitions;
    protected string $workflowName = 'Workflow';

    protected Workflow $workflow;
    protected string $className;

    public function __construct()
    {
        $this->initializePlaces();
        $this->initializeTransitions();

        $this->workflow = new Workflow(
            definition: $this->getDefinition(),
            markingStore: $this->getMarking(),
            name: $this->workflowName
        );
    }

    public function getWorkflowClassName(): string
    {
        return $this->className;
    }

    public function setWorkflowClassName(string $className): WorkflowInterface
    {
        $this->className = $className;
        return $this;
    }

    public function addPlace(string $place): self
    {
        array_push($this->places, $place);
        return $this;
    }

    public function addPlaces(array $places): self
    {
        $this->places = array_merge($this->places, $places);
        return $this;
    }

    public function addTransition(Transition $transition): self
    {
        array_push($this->transitions, $transition);
        return $this;
    }

    public function addTransitions(array $transitions): self
    {
        $this->transitions = array_merge($this->transitions, $transitions);
        return $this;
    }

    public function getWorkflow(): Workflow
    {
        return $this->workflow;
    }

    public function can(object $object, string $transitionName): bool
    {
        return $this->workflow->can($object, $transitionName);
    }


    public function getDefinition(): Definition
    {
        $definitionBuilder = new DefinitionBuilder();
        $definitionBuilder->addPlaces($this->places);
        $definitionBuilder->addTransitions($this->transitions);
        return $definitionBuilder->build();
    }

    #[Pure]
    public function getMarking(bool $singleState = true, string $propertyName = 'status'): MarkingStoreInterface
    {
        return new MethodMarkingStore($singleState, $propertyName);
    }

    public function apply(object $subject, string $state): object
    {
        $this->getWorkflow()->apply($subject, $state);
        return $subject;
    }

    public function getAvailableTransitions(object $subject): array
    {
        $availableTransitions = [];
        foreach ($this->transitions as $transition) {
            /** @var $transition Transition */
            if($this->getWorkflow()->can($subject, $transition->getName())) {
                $availableTransitions[] = $transition->getName();
            }
        }
        return array_unique($availableTransitions);
    }
}