# Contributing

## Flow

1. Branch from an up-to-date `main`: `git switch -c feat/short-description`.
2. Keep the branch small and short-lived. Merge within days, not weeks; hide
   unfinished work behind a flag instead of keeping the branch open.
3. Run the checks locally (the pre-commit hook covers linting of staged files):

   ```bash
   composer lint && composer test:unit
   npm run lint:js --prefix wp-content/themes/custom-theme
   npm run lint:css --prefix wp-content/themes/custom-theme
   npm run test:unit --prefix wp-content/themes/custom-theme
   ```

   Integration and E2E tests need a database or Docker; see the README. CI runs
   them on every pull request either way.
4. Open a pull request against `main` and fill in the template.
5. Once CI is green and the PR is approved, **squash merge**. Delete the branch.

## Commit and PR titles

Pull request titles follow [Conventional Commits](https://www.conventionalcommits.org/),
because the title becomes the commit message on `main`:

```
feat(site-core): add event post type
fix(theme): escape example block heading once
chore(deps): bump wordpress to 7.1.3
ci: run PHPUnit on PHP 8.4
docs(adr): record test library decision
```

Common types: `feat`, `fix`, `refactor`, `test`, `docs`, `chore`, `ci`, `build`.
Mark breaking changes with `!` (`feat!: …`) and explain them in the body.

## Decisions

A change that alters the architecture, a boundary or a tool choice comes with
a new ADR in `docs/adr/`, numbered sequentially.
