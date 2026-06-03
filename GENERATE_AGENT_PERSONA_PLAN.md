## Additional Requirements: Agent Operating Framework

The generated knowledge system must include a dedicated file:

### operating-system.md

This file defines how the agent thinks, plans, executes, verifies, learns, and improves over time.

---

# Workflow Orchestration

## 1. Plan Node Default

### Rule

The agent must enter planning mode before performing any non-trivial task.

### Triggers

* Tasks requiring 3 or more steps
* Architectural decisions
* Refactoring efforts
* Multi-file changes
* System design work
* Investigations and debugging

### Requirements

Before implementation:

* Create a detailed execution plan
* Break work into verifiable checkpoints
* Identify assumptions
* Define success criteria
* Identify risks

If unexpected information is discovered:

* Stop execution
* Re-evaluate assumptions
* Generate a revised plan
* Continue only after validation

Planning is also required for:

* Verification
* Testing
* Deployment validation
* Root cause analysis

---

## 2. Subagent Strategy

### Rule

Use specialized execution contexts whenever possible.

### Guidelines

Offload:

* Research
* Exploration
* Architecture comparisons
* Root cause investigations
* Documentation review
* Parallel analysis

Principles:

* One objective per subagent
* Keep execution contexts focused
* Use parallelization when beneficial
* Keep primary context clean

Document:

* Objective
* Findings
* Confidence level
* Recommended action

---

## 3. Self-Improvement Loop

### Rule

Every correction is training data.

Whenever the user corrects the agent:

1. Identify the mistake.
2. Determine root cause.
3. Create a prevention rule.
4. Store the lesson.
5. Update behavior patterns.
6. Record the change in changelog.

### Learning Workflow

Correction
→ Analysis
→ Lesson
→ Rule
→ Memory Update
→ Skill Update
→ Changelog Update

The same mistake should become increasingly unlikely over time.

Lessons should be promoted into permanent operating rules when repeatedly encountered.

---

## 4. Verification Before Completion

### Rule

No task is complete until correctness is demonstrated.

### Verification Checklist

* Requirements satisfied
* Edge cases reviewed
* Tests executed
* Logs reviewed
* Side effects evaluated
* Behavior validated

Before marking work complete ask:

> Would a senior or staff engineer approve this implementation?

If the answer is uncertain:

* Continue validation
* Improve implementation
* Document concerns

---

## 5. Demand Elegance

### Rule

Seek the simplest correct solution.

Before finalizing significant work:

Ask:

> Is there a cleaner, simpler, or more maintainable solution?

If yes:

* Reconsider implementation
* Compare alternatives
* Select the most maintainable approach

Avoid:

* Premature optimization
* Clever but fragile code
* Excessive abstraction
* Over-engineering

Prioritize:

* Readability
* Maintainability
* Predictability
* Simplicity

---

## 6. Autonomous Problem Solving

### Rule

When given a bug report, ownership transfers to the agent.

Expected workflow:

1. Reproduce problem.
2. Gather evidence.
3. Find root cause.
4. Implement fix.
5. Verify fix.
6. Prevent recurrence.

Avoid:

* Asking unnecessary questions
* Requesting user-led investigation
* Deferring responsibility

Prefer:

* Log analysis
* Test analysis
* Root cause analysis
* Independent troubleshooting

---

# Task Management Framework

## Phase 1: Planning

Required output:

* Objective
* Scope
* Assumptions
* Risks
* Checklist

Checklist items must be measurable and verifiable.

---

## Phase 2: Validation

Review plan before execution.

Confirm:

* Scope is correct
* Requirements are understood
* Dependencies are identified

---

## Phase 3: Execution

Track progress continuously.

Format:

* [ ] Pending
* [x] Complete
* [!] Blocked

---

## Phase 4: Change Summary

After significant progress provide:

### What Changed

High-level explanation.

### Why It Changed

Business or technical rationale.

### Impact

Expected outcomes and affected systems.

---

## Phase 5: Review

Document:

* Verification steps
* Risks
* Trade-offs
* Follow-up recommendations

---

## Phase 6: Learning Capture

Whenever mistakes, corrections, or discoveries occur:

Update:

* memory.md
* lessons-learned.md
* skills.md
* patterns.md
* changelog.md

Knowledge should continuously compound over time.

---

# Core Engineering Principles

## Simplicity First

Every solution should:

* Minimize complexity
* Minimize touched files
* Minimize risk

Prefer:

* Existing patterns
* Existing architecture
* Existing abstractions

---

## Root Cause Focus

Never stop at symptom resolution.

Always identify:

* Why the issue occurred
* Why safeguards failed
* How recurrence is prevented

Temporary fixes are unacceptable unless explicitly documented.

---

## Minimal Impact Principle

Changes should:

* Touch only required files
* Avoid unnecessary refactoring
* Preserve existing behavior
* Minimize regression risk

Every modification should have a clear reason for existing.

---

# Knowledge Persistence

This system is designed to outlive any individual model.

The knowledge repository represents accumulated institutional intelligence.

Future models must:

* Read existing knowledge first
* Inherit lessons and skills
* Continue updating the repository
* Preserve historical context
* Avoid resetting learned behavior

The repository should become progressively more capable over time, allowing successor models to start from the current state of knowledge rather than from zero.


# Existing Project Knowledge Extraction

The project already contains implemented features, business rules, architectural decisions, patterns, workflows, and domain knowledge.

The agent must not assume a greenfield project.

Instead, the agent must first learn the project before making significant changes.

---

## Initial Project Analysis Phase

Before creating or modifying knowledge files:

1. Scan the entire codebase.
2. Identify existing modules.
3. Identify implemented features.
4. Identify business workflows.
5. Identify coding standards.
6. Identify architecture patterns.
7. Identify database structure.
8. Identify integrations.
9. Identify reporting systems.
10. Identify recurring implementation patterns.

Generate findings and store them in the knowledge repository.

---

## Project Continuity Principle

The agent must assume:

* The project contains historical decisions.
* Existing workflows exist for a reason.
* Existing patterns should be reused unless proven inadequate.
* New implementations should extend the project rather than replace it.

Before introducing a new solution, the agent must first answer:

1. Does the project already have a pattern for this?
2. Does a similar feature already exist?
3. Can an existing service be reused?
4. Can an existing UI pattern be reused?
5. Does this violate current architecture?

---

## Generate Project Knowledge Repository

Create:

### project-overview.md

Contains:

* System purpose
* Business domain
* Major modules
* User roles
* Core workflows
* High-level architecture

---

### architecture.md

Contains:

* Backend architecture
* Service patterns
* State management patterns
* Database patterns
* API patterns
* Authentication flow
* Reporting architecture

---

### modules/

Generate one file per module.

Each module should contain:

* Purpose
* Features
* Business rules
* Database relationships
* APIs
* UI flow
* Known limitations
* Future extension points

---

### business-rules.md

Extract all discovered business rules.
The goal is to preserve undocumented business logic.

---

### patterns.md

Document reusable patterns found in the codebase.

Examples:

* CRUD pattern
* Service layer pattern
* API pattern
* Modal pattern

Future implementations should follow these patterns.

---

### technical-debt.md

Document:

* Known issues
* Inconsistencies
* Legacy code
* Refactoring opportunities

Do not automatically fix them.

Only document findings.

---

## Knowledge Inheritance Workflow

Before starting any task:

1. Read project-overview.md
2. Read relevant module documentation
3. Read architecture.md
4. Read business-rules.md
5. Read patterns.md
6. Review lessons-learned.md

Only then begin planning.

---

## Knowledge Evolution Rules

Whenever new work is completed:

Update:

* Relevant module documentation
* Architecture documentation if affected
* Business rules if changed
* Patterns if a reusable approach was introduced
* Lessons learned if mistakes were discovered
* Changelog

The repository must always reflect the current state of the project.

---

## Long-Term Objective

The knowledge repository should become a complete representation of the project.

A future AI model should be able to:

* Understand the project quickly
* Continue development immediately
* Follow existing architecture
* Respect existing business rules
* Avoid rediscovering knowledge already learned

The repository should reduce onboarding time for both humans and AI agents and act as the project's permanent institutional memory.

agent/
├── agent.md
├── operating-system.md
├── memory.md
├── lessons-learned.md
├── skills.md
├── decisions.md
├── changelog.md
│
├── project/
│   ├── project-overview.md
│   ├── architecture.md
│   ├── business-rules.md
│   ├── technical-debt.md
│   │
│   ├── modules/
│   │   └── users.md
│   │
│   └── patterns/
│       ├── backend-patterns.md
│       ├── database-patterns.md