# Frontend-Only Spec for Claude: Mobile-Friendly Schedule + Patrol Modals + Volunteer View Fixes

## Hard constraints

* **Frontend-only** : do not add/modify backend endpoints, database tables, or server logic.
* Assume existing pages already fetch schedule/patrol/user data somehow. Your changes must **consume existing data** and work with current API responses.
* Any “save/create/delete” actions must be implemented as **UI flows only** unless the app already has working endpoints you can call. If endpoints exist, you can call them; if not, implement **mock/local-state** behavior with clear “Not saved” indicators.

---

## Deliverables

1. **Schedules page**
   * Click a date → open a **center modal** to Add or Edit patrol
   * Fully **mobile responsive**
2. **Remove/Hide Patrols page in the UI**
   * No backend changes; just remove nav link and route access from the frontend router if possible
3. **Volunteer page fixes**
   * Volunteer-facing layout for the calendar/table (currently “very off”)
   * Mobile-first improvements: readable cells, stacked layout, proper spacing, tap targets

---

## 1) Schedules Page: Calendar Click → Modal Add/Edit (Frontend-only)

### UI behavior

* Clicking a date cell opens a modal:
  * If that date has **no patrol** in the currently loaded data → **Add Patrol** mode
  * If that date has **an existing patrol** → **Edit Patrol** mode

### How to detect “existing patrol”

Use whatever schedule data already exists in frontend state. Typical patterns:

* `patrols` array with `{ patrolNr, patrolDate, patrolDescription, SuperUserNr, patrol_status, roster[] }`
* or grouped by date in a map/dictionary

Frontend rule:

* `hasPatrol = patrols.some(p => p.patrolDate === selectedDateISO)`

If multiple patrols per date appear in data:

* Show a **list inside the modal** to select which patrol to edit (simple dropdown), default to first.

---

## 2) Modal UX (Mobile-friendly by default)

### Modal structure (responsive)

* Overlay: full-screen dim background
* Modal container:
  * Desktop/tablet: centered, max-width ~600px
  * Mobile: full-width bottom sheet OR centered with `width: calc(100% - 24px)` and max-height 85vh
* Scrolling:
  * modal body scrolls, header/footer sticky

### Close interactions

* Close button top right
* Escape closes (desktop)
* Tap outside closes (optional; keep if it doesn’t conflict with mobile scrolling)

### Accessibility

* Focus trap in modal
* `aria-modal="true"`, labelled title
* Buttons have visible focus state

---

## 3) Add Patrol Modal (UI-only)

### Title

**Add Patrol — {Date}**

### Fields

1. **Volunteers** (multi-select UI)
   * Use the already loaded volunteer list if present in frontend.
   * If not present, render an empty state: “Volunteer list unavailable.”
   * Mobile: use searchable chips or a list with checkboxes.
2. **Super** (single select)
   * Same: use any loaded “super users” list if present.
3. **Volunteer limit** (number input)
   * UI validation only:
     * integer, >= 0
     * if selected volunteers exceed limit → show error and disable primary action

### Primary action behavior (frontend-only)

* If there is an existing create endpoint in frontend services, call it.
* If not, perform  **optimistic local update** :
  * Add a “temporary patrol” object into UI state with a `tempId` and a badge “Unsaved”
  * Show a toast/banner: “Created in UI only (backend not connected).”

### Buttons

* Cancel
* Add Patrol (disabled if invalid)

---

## 4) Edit Patrol Modal (UI-only)

### Title

**Edit Patrol — {Date}**

### Prefill

* Volunteers pre-selected based on roster
* Super pre-selected
* Limit prefilled if UI has a place to store it (see below)

### Editable

* Change volunteers
* Change super
* Change limit
* Delete patrol (UI-only)

### Delete behavior (frontend-only)

* Confirmation dialog inside modal
* If backend delete exists, call it; otherwise remove from local state and mark as “Unsaved changes”

### Buttons

* Cancel
* Save Changes
* Delete Patrol (danger)

---

## 5) Volunteer Limit Handling (Frontend-only, no DB changes)

Since you can’t change backend or schema, implement limit as:

* **Pure UI field** used for validation and display only
* Store it in one of these (frontend-only):
  1. Component state per open modal (least persistent)
  2. Browser storage keyed by patrol/date (recommended):
     * `localStorage["patrolLimit:YYYY-MM-DD"] = "8"`
     * For patrols with IDs: `localStorage["patrolLimit:patrolNr:11"] = "8"`
* Display “Limit is stored locally on this device” helper text in small caption.

This avoids backend changes while still meeting UX expectation.

---

## 6) Calendar Rendering Improvements (Mobile & Volunteer view)

### Current issue

“The date table is very off” for volunteer POV — likely:

* columns too narrow
* header not aligned
* overflow / pills breaking layout
* non-responsive grid

### Replace the calendar layout approach

Implement responsive layout with two modes:

#### Desktop / Tablet (>= 768px)

* Standard 7-column grid
* Fixed header row with weekday names
* Date cells with:
  * date number top-left
  * patrol pills stacked underneath
  * max 2 pills visible + “+N more” link

#### Mobile (< 768px)

Switch to a **list/agenda** style (most reliable):

* Show week selector or month selector at top
* Render days vertically:
  * “Fri 27” row header
  * below: patrol entries as cards/pills
  * tap day opens modal

    This eliminates squished 7-column grids on phones and fixes “off” alignment.

If you must keep a grid on mobile:

* Use horizontal scroll:
  * 7 columns but container scrolls
  * minimum column width like 120–160px
  * sticky weekday header

 **Preferred** : mobile agenda/list view.

---

## 7) Volunteer Page Fixes (Volunteer point of view)

### Volunteer page goals

* Show only what a volunteer needs:
  * upcoming patrols they’re assigned to
  * available/open patrols (if your frontend has that notion)
  * clear status badges from `patrol_status` (Not Released, Released, etc.)
* Remove admin-only clutter (create/edit controls) if role is VOLUNTEER

### Volunteer UI layout

**Top section**

* “My Upcoming Patrols”
  * list cards: date, description, super name, status badge
* Quick filter: “This month / Next month”

**Calendar section**

* Mobile: agenda list mode
* Desktop: calendar grid with simpler pill styling

### Interaction rules for volunteers

* If volunteer role:
  * date click opens **view-only** modal by default
  * If the existing app allows volunteering/claiming slots, show CTA; otherwise don’t invent it
* Keep edit/create hidden unless role is admin/manager (based on whatever role flag exists in frontend state)

---

## 8) Styling / CSS Requirements (mobile friendliness)

### Tap targets

* Minimum 44px height for clickable elements (date cells, pills, buttons)

### Pills

* Don’t let text overflow destroy layout
* Use:
  * single-line clamp with ellipsis
  * wrap into two lines max on desktop
* Spacing between pills: 6–8px

### Calendar cell sizing

* Desktop: min height 110–140px
* Mobile: avoid 7-column squeeze

### Sticky elements

* Sticky weekday header (desktop grid)
* Sticky modal footer with primary actions

---

## 9) Implementation Notes for Claude (frontend-only)

* Do not create new backend calls.
* Reuse existing data fetching hooks/services.
* If save/delete endpoints aren’t available:
  * still implement UI state changes
  * mark changed patrols with “Unsaved” badge
  * persist temporary edits in localStorage (optional but recommended)

---

## Acceptance Criteria

* ✅ Clicking a date opens a modal (Add or Edit based on existing patrol)
* ✅ Modal works well on mobile (no overflow, scrollable content, large buttons)
* ✅ Volunteer page calendar/table alignment is fixed by switching to agenda view on mobile
* ✅ Volunteer view hides admin controls and presents “My Upcoming Patrols” clearly
* ✅ No backend/schema changes required; frontend compiles and runs
