# MaxPost Security

**Document:** `11-Security.md`  
**Version:** 1.0 Alpha  
**Status:** Living Specification

## Purpose

Security protects user trust, software distribution and platform integrity. Security requirements apply to the website, WordPress administration, REST API, downloadable applications and build process.

## Core principles

- Least privilege
- Explicit trust boundaries
- Validate before storage
- Escape at output
- Fail safely
- No security through obscurity
- No secrets in source control
- Signed and verifiable releases where practical

## WordPress writes

Every privileged write must include:

1. Nonce verification
2. Capability check
3. Autosave and revision handling
4. Input sanitization
5. Type validation

Example:

```php
if ( ! isset( $_POST['maxpost_nonce'] ) ||
     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maxpost_nonce'] ) ), 'maxpost_save_software' ) ) {
    return;
}

if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
}
```

## Output safety

Use context-specific escaping:

```text
esc_html()
esc_attr()
esc_url()
wp_kses_post()
```

Do not escape before storage unless required by the field contract. Sanitize on input and escape on output.

## REST API

Every endpoint requires:

- explicit `permission_callback`
- strict argument schema
- input validation
- output allowlist
- predictable errors
- rate limits for writes

Public endpoints must never expose:

- private post meta
- user emails
- tokens
- filesystem paths
- stack traces
- WordPress salts

## File distribution

Downloadable executables must have:

- canonical HTTPS URL
- version
- file size
- SHA-256 checksum
- publication date
- malware scan in release workflow
- optional code signing when feasible

Never replace a published binary silently without changing version or checksum.

## Uploads

Restrict allowed MIME types. Executable distribution must not rely on ordinary untrusted public uploads without controls.

Requirements:

- filename normalization
- MIME and extension validation
- no execution from upload directories
- administrator-only release upload
- checksum generation

## Secrets

Secrets belong in environment variables or secure platform configuration.

Never commit:

- API keys
- passwords
- private certificates
- signing keys
- OAuth tokens
- production database exports

## Dependencies

For every dependency:

- document purpose
- pin or constrain versions
- review licenses
- monitor security advisories
- remove unused packages

Avoid dependencies for trivial functionality.

## Desktop applications

Applications must:

- use HTTPS
- validate update metadata
- avoid automatic browser opening without user action
- store settings in user-scoped locations
- avoid administrator privileges unless necessary
- never execute downloaded content without verification
- expose network behavior in privacy documentation

## Update security

The updater must verify at least:

- expected HTTPS origin
- version format
- checksum
- downloaded file integrity

Future target: signed update manifests and signed binaries.

## Logging

Logs must be useful without exposing sensitive data.

Do not log:

- passwords
- tokens
- full request authorization headers
- personal file paths unless essential and user-approved

Production errors must not display stack traces to visitors.

## Abuse prevention

Write endpoints require:

- request size limits
- allowlisted event names
- rate limiting
- origin or authentication controls
- rejection of unexpected fields

## Incident response

1. Confirm and scope the issue.
2. Preserve evidence without exposing user data.
3. Revoke compromised credentials.
4. Patch and test.
5. Publish a clear advisory when users are affected.
6. Document preventive actions.

See root `SECURITY.md` for reporting instructions.

## Release checklist

- [ ] No secrets committed
- [ ] Nonces and capabilities verified
- [ ] REST permissions reviewed
- [ ] Inputs sanitized
- [ ] Outputs escaped
- [ ] Dependency audit completed
- [ ] Binary checksum published
- [ ] Debug output disabled in production
