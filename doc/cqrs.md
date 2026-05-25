# CQRS Command Standard - User Module

For all future implementation tasks, agents or developers modifying the `User` module MUST adhere to the following strict conventions:

## 1. Directory Structure

Place all CQRS elements under a single feature namespace directory:
`src/User/Application/<FeatureName>/`

Example:
- `ResetPasswordCommand.php`
- `ResetPasswordCommandHandler.php`
- `ResetPasswordCommandValidator.php`

## 2. Command Class Definition

- Class Name: `<FeatureName>Command`
- Attribute: `#[Symfony\Component\Messenger\Attribute\AsMessage]`
- Properties: Declared using constructor promotion as `public readonly`.

## 3. Validator Class Definition

- Class Name: `<FeatureName>CommandValidator` or `<FeatureName>Validator` (matching middleware naming logic)
- Attribute: `#[App\Common\Attribute\AsMessageValidator]`
- Method: `__invoke(<FeatureName>Command $command): void`
- Logic: Queries the repository to run domain-specific validations (throwing exceptions when they fail).

## 4. Handler Class Definition

- Class Name: `<FeatureName>CommandHandler` or `<FeatureName>Handler`
- Attribute: `#[Symfony\Component\Messenger\Attribute\AsMessageHandler]`
- Method: `__invoke(<FeatureName>Command $command): void`
- Logic: Validates entity existence, executes logic, hashes passwords where necessary, persists changes, and dispatches corresponding domain events (e.g., using `Symfony\Component\EventDispatcher\EventDispatcherInterface`).
