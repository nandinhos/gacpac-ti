---
title: Database Schema
type: technical
permalink: context/database-schema
---

# Database Schema Summary

**Source**: `project-docs/tech-reference/DATABASE_SCHEMA.md`

**Core Entities**:
1. **sectors**: Military units/departments.
2. **military_users**: Users with ranks and roles (`user`, `commission`, `admin`).
3. **assets**: IT Assets. Key attributes: `qr_code`, `status`, `custodian_user_id`.
   - Has legacy/compatibility fields (`brand` vs `manufacturer`, `condition` vs `condition_rating`).
4. **custody_logs**: Lending records (cautelas). Links users to assets via `custody_assets`.
5. **inventory_records**: Stocktaking sessions.
   - Related: `inventory_assets`, `uncatalogued_items`, `reopen_history`.

**Key Relationships**:
- Users belong to Sectors.
- Assets belong to Sectors and can be in Custody of Users.
- Custody Logs track Assets borrowed by Users.
