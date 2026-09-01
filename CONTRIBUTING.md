# Contributing to Planka PHP SDK

Thank you for considering contributing to `decole/planka-php-sdk`! We welcome bug fixes, new features, documentation improvements, and feedback.

---

## How to Contribute

### 1. Reporting Bugs & Requesting Features
- **Bug Reports:** If you discover a bug or compatibility issue with Planka v2, please check existing GitHub Issues first. If it's not reported, create a new issue with:
  - A clear title and detailed description.
  - Planka server version (e.g. Planka v2.2.1) and PHP version.
  - Minimal code snippet or steps to reproduce the issue.
- **Feature Requests:** Open an Issue to propose new API endpoints or controller features before submitting a large PR.

---

### 2. Development & Pull Request Workflow

1. **Fork the Repository**
   Fork `decole/planka-php-sdk` to your GitHub account and clone it locally:
   ```bash
   git clone https://github.com/YOUR-USERNAME/planka-php-sdk.git
   cd planka-php-sdk
   composer install
   ```

2. **Create a Feature Branch**
   Create a descriptive branch for your work:
   ```bash
   git checkout -b feature/short-description
   # or for bug fixes:
   git checkout -b fix/short-description
   ```

3. **Write Code & Add Tests**
   - Follow existing architecture and naming conventions.
   - For new features or bug fixes, add unit tests in `tests/Unit/`.
   - If recording real Planka responses as test fixtures, place JSON files under `tests/Fixtures/`.

4. **Verify Quality Standards**
   Before submitting your PR, ensure all checks pass:
   ```bash
   # 1. Run Unit tests
   composer test

   # 2. Run Psalm static analysis
   ./vendor/bin/psalm --no-cache

   # 3. Format code according to project style rules
   composer fix-cs
   ```

5. **Commit & Push**
   Write clear, concise commit messages matching repository conventions:
   ```bash
   git add .
   git commit -m "feat(card): add support for card cover removal"
   git push origin feature/short-description
   ```

6. **Submit a Pull Request**
   - Open a PR against the `master` branch.
   - Describe the changes made and link any relevant GitHub Issues.

---

## Code Style & Standards

- **PHP Version:** PHP ^8.1
- **Code Style:** PSR-12 enforced via `php-cs-fixer`.
- **Strict Typing:** All new PHP files must start with `declare(strict_types=1);`.
- **Static Analysis:** Code should pass Psalm static analysis without error level 1-3 regressions.

Thank you for helping make the Planka PHP SDK better!
