<?php

namespace Aashan\Workflow\Workflows;

use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

interface WorkflowInterface
{

    public function addPlace(string $place): self;

    public function addPlaces(array $places): self;

    public function addTransition(Transition $transition): self;

    public function addTransitions(array $transitions): self;

    public function initializeTransitions(): self;

    public function initializePlaces(): self;

    public function getWorkflow(): Workflow;

    public function getWorkflowClassName(): string;

    public function setWorkflowClassName(string $className): self;

    public function can(object $object, string $transitionName);

    public function getMarking(): MarkingStoreInterface;

    public function getDefinition(): Definition;

    public function apply(object $subject, string $state): object;

    public function getAvailableTransitions(object $subject): array;
}