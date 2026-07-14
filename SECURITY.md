# Security Policy

## Reporting a vulnerability

Do not publish security vulnerabilities in a public issue.

Report privately to the project owner with:

- affected component
- affected version
- reproduction steps
- expected and actual behavior
- possible impact
- suggested mitigation, if known

## Security principles

MaxPost software must:

- validate all untrusted input
- avoid unnecessary network access
- avoid hidden telemetry
- use secure update channels
- verify downloadable files with checksums where practical
- never bundle unrelated third-party software
- store no secrets in source control

## WordPress rules

- Nonces for privileged writes
- Capability checks
- Sanitization before storage
- Escaping at output boundaries
- Strict REST permission callbacks
- No direct SQL without prepared statements
- No executable uploads in public media paths

## Supported versions

Only the latest stable release receives security fixes during the early development stage.
