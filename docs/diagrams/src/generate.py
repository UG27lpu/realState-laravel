"""Generate every Estatify architecture diagram as SVG.

Run from the repo root:

    python3 docs/diagrams/src/generate.py

Each diagram writes to docs/diagrams/svg/<id>.svg. PNG conversion happens in
the shell wrapper (render.sh) via rsvg-convert.
"""
from __future__ import annotations

import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
from diagram_lib import SVG, PALETTE  # noqa: E402

OUT = os.path.normpath(os.path.join(HERE, "..", "svg"))
os.makedirs(OUT, exist_ok=True)


# ---------- 01. C4 — System Context ----------
def diagram_01_system_context() -> SVG:
    d = SVG(1280, 820, "01 — System Context (C4 Level 1)")

    # actors on the left
    d.actor(140, 220, "Buyer / Browser")
    d.actor(140, 400, "Property Agent")
    d.actor(140, 580, "Administrator")
    d.actor(140, 740, "Guest visitor")

    # core system in the centre
    d.rect(420, 250, 440, 320, fill=PALETTE["surface"],
           stroke=PALETTE["accent"], sw=2.5)
    d.text(640, 290, "Estatify", size=22, weight=700, fill=PALETTE["accent"])
    d.text(640, 318, "Real-estate listing & moderation platform",
           fill=PALETTE["ink_dim"], size=12, italic=True)
    d.multiline(640, 360, [
        "Laravel 13 web application",
        "Property catalogue · search · wishlist",
        "Buyer ↔ agent chat · appointments",
        "Admin moderation · PDF reports · QR verify",
        "EMI & investment calculators · demo AI tools",
    ], size=12, line_height=22)

    # external systems on the right
    d.cloud(1000, 100, 230, 110, "Gravatar avatars")
    d.cloud(1000, 250, 230, 110, "Browser / device storage\n(compare session)")
    d.cloud(1000, 400, 230, 110, "Mail relay (log driver)")
    d.cloud(1000, 550, 230, 110, "Local filesystem\n(public disk)")
    d.cloud(1000, 700, 230, 90, "QR scanner (mobile)")

    # actor → system edges
    for ay, label in [(220, "browses, books visits, chats"),
                      (400, "lists properties, replies to enquiries"),
                      (580, "approves / rejects, manages users"),
                      (740, "search & compare (no account)")]:
        d.path(f"M180 {ay} C 280 {ay}, 360 {ay}, 420 {ay+10}",
               color=PALETTE["accent"], sw=1.8, marker_end="arrow-accent")
        d.text(300, ay - 8, label, size=11, fill=PALETTE["ink_dim"])

    # system → externals
    for cy, label in [(155, "fetches user avatars"),
                      (305, "stores 'compare' list in session"),
                      (455, "queued mail notifications"),
                      (605, "stores images / videos / docs"),
                      (745, "QR ↦ /verify/{slug}")]:
        d.path(f"M860 {cy} C 920 {cy}, 960 {cy}, 1000 {cy}",
               color=PALETTE["accent2"], sw=1.6, marker_end="arrow-emerald")
        d.text(930, cy - 8, label, size=11, fill=PALETTE["ink_dim"])

    return d


# ---------- 02. C4 — Container Diagram ----------
def diagram_02_containers() -> SVG:
    d = SVG(1400, 900, "02 — Container Diagram (C4 Level 2)")

    # client tier
    d.section_header(60, 80, 380, "Client tier")
    d.card(80, 120, 340, 110, "Browser (Blade-rendered)",
           subtitle="Alpine.js + Tailwind + Vite",
           body=["server-rendered HTML, progressive JS",
                 "fetch() to JSON demo endpoints"],
           accent=PALETTE["accent"])
    d.card(80, 250, 340, 110, "Mobile camera",
           subtitle="QR scanner",
           body=["loads /verify/{slug} verification page"],
           accent=PALETTE["accent"])

    # web tier
    d.section_header(500, 80, 480, "Application tier")
    d.rect(490, 120, 500, 460, fill=PALETTE["surface"],
           stroke=PALETTE["accent2"], sw=2)
    d.text(740, 152, "Estatify Laravel app", size=15, weight=700,
           fill=PALETTE["accent2"])
    d.text(740, 172, "PHP 8.3 · Laravel 13", size=11, fill=PALETTE["ink_dim"],
           italic=True)

    d.card(510, 195, 220, 90, "Web (HTTP)",
           subtitle="routes/web.php + Blade",
           accent=PALETTE["accent"])
    d.card(750, 195, 220, 90, "Console / Tinker",
           subtitle="artisan",
           accent=PALETTE["accent"])
    d.card(510, 300, 220, 90, "Queue worker",
           subtitle="QUEUE_CONNECTION=database",
           accent=PALETTE["warn"])
    d.card(750, 300, 220, 90, "Scheduler",
           subtitle="schedule:run",
           accent=PALETTE["warn"])
    d.card(510, 405, 460, 160, "Domain services & actions",
           subtitle="PropertySearchService · PropertyMediaService · PdfService · Demo\\AiDescriptionService …",
           body=["StorePropertyAction · UpdatePropertyAction · RegisterUserAction",
                 "AnalyticsService · EmiCalculatorService · InvestmentReturnService"],
           accent=PALETTE["violet"])

    # data tier
    d.section_header(1040, 80, 320, "Data tier")
    d.db_cylinder(1060, 120, 280, 130, "Primary database",
                  sub="SQLite (default) · MySQL ready")
    d.db_cylinder(1060, 270, 280, 110, "Sessions",
                  sub="database driver", color=PALETTE["warn"])
    d.db_cylinder(1060, 400, 280, 110, "Queue + Cache",
                  sub="database driver", color=PALETTE["warn"])
    d.db_cylinder(1060, 530, 280, 110, "Notifications table",
                  sub="UUID + morph", color=PALETTE["violet"])
    d.card(1060, 660, 280, 120, "Public filesystem disk",
           subtitle="storage/app/public",
           body=["property/images · /videos · /docs",
                 "served via storage symlink"],
           accent=PALETTE["accent"])

    # arrows browser → app
    d.path("M420 175 C 460 175, 470 220, 510 240", color=PALETTE["accent"],
           sw=2, marker_end="arrow-accent")
    d.text(465, 210, "HTTPS", size=11, fill=PALETTE["ink_dim"])
    d.path("M420 305 C 460 305, 470 240, 510 240", color=PALETTE["accent"],
           sw=2, dash="4 4", marker_end="arrow-accent")

    # app → data
    d.path("M970 250 C 1020 250, 1030 180, 1060 180",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")
    d.text(1015, 215, "Eloquent", size=11, fill=PALETTE["ink_dim"])
    d.path("M970 345 C 1020 345, 1030 320, 1060 320",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")
    d.path("M970 350 C 1020 360, 1030 455, 1060 455",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")
    d.path("M970 485 C 1020 510, 1030 585, 1060 585",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")
    d.path("M970 510 C 1020 600, 1030 715, 1060 715",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")

    # external
    d.section_header(60, 620, 380, "External / hosted")
    d.cloud(80, 660, 160, 110, "Gravatar")
    d.cloud(260, 660, 160, 110, "Mail relay")
    d.path("M740 580 C 600 700, 350 720, 245 715", color=PALETTE["edge"],
           sw=1.4, marker_end="arrow", dash="4 4")
    d.text(450, 692, "Notification fan-out", size=11,
           fill=PALETTE["ink_dim"])

    return d


# ---------- 03. C4 — Component Diagram (HTTP layer) ----------
def diagram_03_components() -> SVG:
    d = SVG(1500, 1050, "03 — Component Diagram (C4 Level 3)")

    # column anchors
    col_w = 290
    col_x = [40, 350, 660, 980, 1290]
    headers = ["HTTP / Middleware", "Controllers", "Actions & Services",
               "Models", "Persistence"]
    colors = [PALETTE["accent"], PALETTE["accent2"], PALETTE["violet"],
              PALETTE["pink"], PALETTE["warn"]]
    for x, h, c in zip(col_x, headers, colors):
        d.section_header(x - 10, 80, col_w - 20, h, color=c)

    # column 1 — middleware
    items_mw = [
        ("Auth · Guest", "Laravel built-in"),
        ("EnsureUserHasRole", "admin / agent gates"),
        ("RedirectIfAdmin", "guest routing"),
        ("TrackPropertyView", "view counter"),
        ("VerifyCsrfToken", "Laravel built-in"),
    ]
    for i, (t, s) in enumerate(items_mw):
        y = 130 + i * 130
        d.card(col_x[0] - 10, y, col_w - 20, 100, t, subtitle=s,
               accent=PALETTE["accent"])

    # column 2 — controllers
    items_c = [
        ("PropertyController", "list · show · CRUD · mine"),
        ("ChatController", "start · show · send"),
        ("AppointmentController", "store · index · update"),
        ("WishlistController", "toggle · index"),
        ("CompareController", "session-backed compare"),
        ("ReportController", "PDF receipts & reports"),
        ("Auth\\* + Admin\\*", "login · register · moderation"),
    ]
    for i, (t, s) in enumerate(items_c):
        y = 130 + i * 110
        d.card(col_x[1] - 10, y, col_w - 20, 90, t, subtitle=s,
               accent=PALETTE["accent2"])

    # column 3 — services / actions
    items_s = [
        ("PropertySearchService", "filters + paginator"),
        ("PropertyMediaService", "image/video/doc storage"),
        ("Store/UpdatePropertyAction", "transactional writes"),
        ("RegisterUserAction", "user + role bootstrap"),
        ("PdfService", "dompdf wrapper"),
        ("AnalyticsService", "dashboard aggregates"),
        ("Emi / Investment service", "calculators"),
        ("Demo\\* services", "AI · pricing · legal · dupes"),
    ]
    for i, (t, s) in enumerate(items_s):
        y = 130 + i * 102
        d.card(col_x[2] - 10, y, col_w - 20, 82, t, subtitle=s,
               accent=PALETTE["violet"])

    # column 4 — models
    items_m = [
        ("User", "+ HasRoles (Spatie)"),
        ("Property", "+ SoftDeletes"),
        ("PropertyImage / Video / Doc", "media siblings"),
        ("Conversation · Message", "chat tree"),
        ("Appointment", "booking"),
        ("Wishlist", "user ↔ property"),
        ("Notification (DB)", "fan-out target"),
    ]
    for i, (t, s) in enumerate(items_m):
        y = 130 + i * 115
        d.card(col_x[3] - 10, y, col_w - 20, 95, t, subtitle=s,
               accent=PALETTE["pink"])

    # column 5 — persistence
    items_p = [
        ("users table", "+ Spatie pivot tables"),
        ("properties table", "indexes on type/status/city"),
        ("property_* media tables", "FK cascade"),
        ("conversations / messages", "unique buyer/agent/prop"),
        ("appointments table", "schedule index"),
        ("wishlists", "unique user/property"),
        ("notifications table", "UUID + morph"),
        ("storage/app/public", "filesystem disk"),
    ]
    for i, (t, s) in enumerate(items_p):
        y = 130 + i * 100
        d.card(col_x[4] - 10, y, col_w - 20, 80, t, subtitle=s,
               accent=PALETTE["warn"])

    # connector arrows between adjacent columns
    for c in range(4):
        x1 = col_x[c] + col_w - 30
        x2 = col_x[c + 1] - 10
        for j in range(0, 6):
            y = 175 + j * 130
            d.path(f"M{x1} {y} C {x1+30} {y}, {x2-30} {y}, {x2} {y}",
                   color=PALETTE["edge"], sw=1, marker_end="arrow",
                   dash="3 4")
    return d


# ---------- 04. Layered architecture ----------
def diagram_04_layered() -> SVG:
    d = SVG(1200, 900, "04 — Layered Architecture (MVC + Services)")

    layers = [
        ("Presentation (Blade · Alpine · Tailwind)",
         ["resources/views/**", "Vite-bundled JS/CSS", "form partials, layouts"],
         PALETTE["accent"]),
        ("HTTP layer (Routes · Middleware · Form Requests)",
         ["routes/web.php", "App\\Http\\Middleware\\*", "App\\Http\\Requests\\*"],
         PALETTE["cyan"]),
        ("Controllers (thin)",
         ["App\\Http\\Controllers\\*", "delegate to actions / services",
          "return Views / Redirects"],
         PALETTE["accent2"]),
        ("Application services & Actions",
         ["App\\Actions\\* (single-use writes)",
          "App\\Services\\* (queries, media, PDFs, demo AI)",
          "Policies + Gates"],
         PALETTE["violet"]),
        ("Domain models (Eloquent)",
         ["App\\Models\\*", "Enums (PropertyType / Status / Approval)",
          "Notifications + Events"],
         PALETTE["pink"]),
        ("Persistence",
         ["SQLite / MySQL", "storage/app/public filesystem",
          "database queue + cache + sessions"],
         PALETTE["warn"]),
    ]
    y = 90
    for title, body, c in layers:
        d.rect(80, y, 1040, 110, fill=PALETTE["surface"], stroke=c, sw=2)
        d.text(110, y + 32, title, size=15, weight=700, fill=c, anchor="start")
        for i, ln in enumerate(body):
            d.text(110, y + 56 + i * 16, "• " + ln, size=12,
                   fill=PALETTE["ink"], anchor="start")
        y += 130

    # downward dependency arrows
    for i in range(5):
        d.path(f"M600 {90 + i * 130 + 110} L 600 {90 + (i+1) * 130}",
               color=PALETTE["accent"], sw=2, marker_end="arrow-accent")

    d.text(600, 870, "Higher layers depend only on the layer directly below.",
           size=12, italic=True, fill=PALETTE["ink_dim"])
    return d


# ---------- 05. Package / Module map ----------
def diagram_05_packages() -> SVG:
    d = SVG(1300, 900, "05 — Package & Module Map (app/)")

    groups = [
        ("Http", [(60, 90), ("Controllers", "16 + Admin/, Auth/, Demo/"),
                  ("Middleware", "EnsureUserHasRole, RedirectIfAdmin, TrackPropertyView"),
                  ("Requests", "PropertyRequest, ChatRequest, …")],
         PALETTE["accent"]),
        ("Models", [(440, 90), ("Domain", "User, Property, Property{Image|Video|Doc}"),
                    ("Comms", "Conversation, Message, Appointment"),
                    ("Saved", "Wishlist")],
         PALETTE["pink"]),
        ("Services", [(820, 90), ("Core",
                                  "Property{Search,Media}, Pdf, Analytics, Emi, Investment"),
                      ("Demo", "AiDescription, PricePrediction, LegalVerification, …")],
         PALETTE["violet"]),
        ("Actions", [(60, 380), ("Auth", "RegisterUserAction"),
                     ("Property", "Store-, UpdatePropertyAction")],
         PALETTE["accent2"]),
        ("Notifications", [(440, 380),
                           ("Channels", "DB + mail"),
                           ("Types", "AppointmentBooked, NewMessage, PropertyDecision")],
         PALETTE["warn"]),
        ("Policies", [(820, 380), ("Authorization",
                                   "PropertyPolicy (view, create, update, delete)")],
         PALETTE["cyan"]),
        ("Enums", [(60, 620), ("State machines",
                               "ApprovalStatus, PropertyStatus, PropertyType, Role")],
         PALETTE["danger"]),
        ("Providers", [(440, 620),
                       ("Boot", "AppServiceProvider — Gates, view composers")],
         PALETTE["ink_dim"]),
        ("View", [(820, 620),
                  ("Composers", "App\\View\\*  →  shared sidebar / nav data")],
         PALETTE["accent"]),
    ]

    for label, items, c in groups:
        x, y = items[0]
        d.rect(x, y, 360, 230, fill=PALETTE["surface"], stroke=c, sw=2)
        d.text(x + 20, y + 30, "App\\" + label, size=15, weight=700,
               fill=c, anchor="start", family=MONO_ALIAS)
        for i, (sub, desc) in enumerate(items[1:]):
            d.text(x + 20, y + 60 + i * 50, sub, size=12, weight=600,
                   anchor="start", fill=PALETTE["ink"])
            d.text(x + 20, y + 76 + i * 50, desc, size=11, anchor="start",
                   fill=PALETTE["ink_dim"])
    return d


MONO_ALIAS = "ui-monospace, SFMono-Regular, Menlo, monospace"


# ---------- 06. ER diagram ----------
def diagram_06_er() -> SVG:
    d = SVG(1500, 1000, "06 — Entity Relationship Diagram")

    def entity(x, y, w, h, name, cols):
        d.rect(x, y, w, h, fill=PALETTE["surface"], stroke=PALETTE["accent"], sw=2)
        d.rect(x, y, w, 30, fill=PALETTE["accent"], stroke="none")
        d.text(x + w / 2, y + 21, name, fill="#0b1220", size=14, weight=700,
               family=MONO_ALIAS)
        for i, (col, typ, mark) in enumerate(cols):
            cy = y + 52 + i * 18
            d.text(x + 12, cy, mark + col, fill=PALETTE["ink"], size=11,
                   anchor="start", family=MONO_ALIAS, weight=600 if mark.strip() == "PK" or "FK" in mark else 400)
            d.text(x + w - 12, cy, typ, fill=PALETTE["ink_dim"], size=11,
                   anchor="end", family=MONO_ALIAS)

    entity(60, 90, 320, 230, "users", [
        ("id", "bigint", "PK "),
        ("name", "string", "    "),
        ("email", "string·uniq", "    "),
        ("password", "string", "    "),
        ("phone, avatar_path, bio", "string?", "    "),
        ("agency_name", "string?", "    "),
        ("is_active", "bool", "    "),
        ("email_verified_at", "ts?", "    "),
        ("timestamps", "ts", "    "),
    ])

    entity(60, 360, 320, 240, "model_has_roles", [
        ("role_id", "FK", "PK "),
        ("model_id", "FK→users", "PK "),
        ("model_type", "string", "PK "),
    ])
    entity(60, 630, 320, 130, "roles", [
        ("id", "bigint", "PK "),
        ("name", "string", "    "),
        ("guard_name", "string", "    "),
    ])

    entity(460, 90, 360, 470, "properties", [
        ("id", "bigint", "PK "),
        ("owner_id", "FK→users", "FK "),
        ("title, slug·uniq", "string", "    "),
        ("description", "text?", "    "),
        ("type", "enum", "    "),
        ("status", "enum", "    "),
        ("approval_status", "enum", "    "),
        ("price", "decimal(14,2)", "    "),
        ("area, area_unit", "dec/string", "    "),
        ("bedrooms, bathrooms,", "smallint?", "    "),
        ("  floors, year_built", "smallint?", "    "),
        ("furnished, parking", "bool", "    "),
        ("address, city, state,", "string", "    "),
        ("  pincode, country", "string", "    "),
        ("lat / lng", "decimal", "    "),
        ("survey_number", "string?", "    "),
        ("legal_verification_status", "string", "    "),
        ("is_featured, view_count", "bool/int", "    "),
        ("nearby_facilities", "json", "    "),
        ("approved_at / rejected_at", "ts?", "    "),
        ("rejection_reason", "text?", "    "),
        ("timestamps + softDeletes", "ts", "    "),
    ])

    entity(900, 90, 280, 140, "property_images", [
        ("id", "bigint", "PK "),
        ("property_id", "FK", "FK "),
        ("path, caption", "string", "    "),
        ("is_cover, sort_order", "bool/int", "    "),
    ])
    entity(900, 260, 280, 120, "property_videos", [
        ("id", "bigint", "PK "),
        ("property_id", "FK", "FK "),
        ("path, thumbnail_path", "string", "    "),
    ])
    entity(900, 410, 280, 140, "property_documents", [
        ("id", "bigint", "PK "),
        ("property_id", "FK", "FK "),
        ("label, type, path", "string", "    "),
        ("is_demo", "bool", "    "),
    ])

    entity(1220, 90, 250, 130, "wishlists", [
        ("id", "bigint", "PK "),
        ("user_id", "FK", "FK "),
        ("property_id", "FK", "FK "),
        ("unique(user, prop)", "", "    "),
    ])

    entity(900, 600, 280, 150, "conversations", [
        ("id", "bigint", "PK "),
        ("property_id", "FK", "FK "),
        ("buyer_id, agent_id", "FK→users", "FK "),
        ("last_message_at", "ts?", "    "),
        ("unique(prop,buyer,agent)", "", "    "),
    ])
    entity(1220, 600, 250, 150, "messages", [
        ("id", "bigint", "PK "),
        ("conversation_id", "FK", "FK "),
        ("sender_id", "FK→users", "FK "),
        ("body", "text", "    "),
        ("read_at", "ts?", "    "),
    ])

    entity(460, 600, 380, 170, "appointments", [
        ("id", "bigint", "PK "),
        ("property_id", "FK", "FK "),
        ("buyer_id, agent_id", "FK→users", "FK "),
        ("scheduled_for", "datetime", "    "),
        ("status", "string", "    "),
        ("notes", "text?", "    "),
    ])
    entity(60, 800, 380, 170, "notifications", [
        ("id", "uuid", "PK "),
        ("type", "string", "    "),
        ("notifiable_type/_id", "morph", "    "),
        ("data", "text(json)", "    "),
        ("read_at", "ts?", "    "),
    ])

    # relations (annotated)
    def rel(x1, y1, x2, y2, label, color=PALETTE["accent2"], dash=None):
        d.path(f"M{x1} {y1} C {(x1+x2)/2} {y1}, {(x1+x2)/2} {y2}, {x2} {y2}",
               color=color, sw=1.6, marker_end="arrow-emerald", dash=dash)
        d.text((x1 + x2) / 2, (y1 + y2) / 2 - 8, label, size=10,
               fill=PALETTE["ink_dim"])

    rel(380, 200, 460, 200, "1—∗ owns")
    rel(820, 160, 900, 160, "1—∗")
    rel(820, 200, 900, 320, "1—∗")
    rel(820, 240, 900, 470, "1—∗")
    rel(820, 280, 1220, 155, "1—∗ wishlisted")
    rel(380, 460, 460, 660, "buyer · 1—∗", color=PALETTE["pink"])
    rel(820, 540, 900, 660, "1—∗ chats", color=PALETTE["pink"])
    rel(820, 600, 460, 680, "1—∗ visits", color=PALETTE["pink"])
    rel(1180, 670, 1220, 670, "1—∗", color=PALETTE["pink"])
    rel(220, 320, 220, 360, "morph", color=PALETTE["warn"])
    rel(380, 870, 380, 870, "", color=PALETTE["warn"])
    d.text(720, 970, "PK = primary key   FK = foreign key   ∗ = many   ? = nullable",
           italic=True, size=11, fill=PALETTE["ink_dim"])
    return d


# ---------- 07. Class diagram (Eloquent) ----------
def diagram_07_class() -> SVG:
    d = SVG(1500, 1000, "07 — Eloquent Class Diagram")

    def cls(x, y, w, h, name, traits, attrs, methods, color=PALETTE["accent"]):
        d.rect(x, y, w, h, fill=PALETTE["surface"], stroke=color, sw=2)
        d.rect(x, y, w, 36, fill=color, stroke="none")
        d.text(x + w / 2, y + 24, name, fill="#0b1220", size=14, weight=700,
               family=MONO_ALIAS)
        ay = y + 56
        if traits:
            d.text(x + 12, ay, "« " + ", ".join(traits) + " »",
                   fill=PALETTE["ink_dim"], size=11, italic=True, anchor="start")
            ay += 18
        for a in attrs:
            d.text(x + 12, ay, "- " + a, size=11, fill=PALETTE["ink"],
                   anchor="start", family=MONO_ALIAS)
            ay += 16
        ay += 6
        d.line(x + 8, ay - 8, x + w - 8, ay - 8, color=PALETTE["edge"], sw=1)
        for m in methods:
            d.text(x + 12, ay, "+ " + m, size=11, fill=PALETTE["ink"],
                   anchor="start", family=MONO_ALIAS)
            ay += 16

    cls(60, 90, 380, 360, "User", ["HasRoles", "Notifiable"],
        ["name, email, password",
         "phone, avatar_path, bio",
         "agency_name, is_active",
         "email_verified_at"],
        ["isAdmin(): bool", "isAgent(): bool",
         "properties(): HasMany",
         "wishlistedProperties(): HasMany",
         "appointments(): HasMany",
         "avatarUrl(): string"],
        color=PALETTE["accent"])

    cls(480, 90, 420, 550, "Property", ["SoftDeletes"],
        ["owner_id, title, slug, description",
         "type: PropertyType, status: PropertyStatus",
         "approval_status: ApprovalStatus",
         "price, area, area_unit",
         "bedrooms, bathrooms, floors, year_built",
         "furnished, parking",
         "address, city, state, pincode, country",
         "latitude, longitude",
         "survey_number, legal_verification_status",
         "is_featured, view_count",
         "nearby_facilities (json)",
         "approved_at, rejected_at, rejection_reason"],
        ["owner(): BelongsTo",
         "images(): HasMany",
         "videos(): HasMany",
         "documents(): HasMany",
         "coverImage(): ?PropertyImage",
         "coverUrl(): string",
         "scopeVisible / OfType / Featured",
         "generateSlug(string): string"],
        color=PALETTE["pink"])

    cls(940, 90, 270, 220, "PropertyImage", [],
        ["property_id", "path, caption",
         "is_cover, sort_order"],
        ["property(): BelongsTo"],
        color=PALETTE["accent2"])
    cls(1230, 90, 250, 220, "PropertyVideo · Document", [],
        ["property_id, path",
         "+ thumbnail / label / is_demo"],
        ["property(): BelongsTo"],
        color=PALETTE["accent2"])

    cls(940, 340, 270, 230, "Conversation", [],
        ["property_id",
         "buyer_id, agent_id",
         "last_message_at"],
        ["property(): BelongsTo",
         "buyer(): BelongsTo",
         "agent(): BelongsTo",
         "messages(): HasMany",
         "counterpartFor(User): ?User"],
        color=PALETTE["violet"])

    cls(1230, 340, 250, 230, "Message", [],
        ["conversation_id",
         "sender_id, body, read_at"],
        ["conversation(): BelongsTo",
         "sender(): BelongsTo"],
        color=PALETTE["violet"])

    cls(60, 500, 380, 220, "Wishlist", [],
        ["user_id, property_id"],
        ["user(): BelongsTo",
         "property(): BelongsTo"],
        color=PALETTE["warn"])

    cls(480, 670, 420, 230, "Appointment", [],
        ["property_id, buyer_id, agent_id",
         "scheduled_for, status, notes",
         "STATUSES = [pending, confirmed, ...]"],
        ["property() · buyer() · agent()",
         "statusBadge(): string"],
        color=PALETTE["danger"])

    cls(940, 600, 540, 290, "Enums", [],
        ["ApprovalStatus { draft, submitted, under_review, approved, rejected }",
         "PropertyStatus { for_sale, for_rent, sold, rented }",
         "PropertyType { land, house, apartment, commercial }",
         "Role { admin, agent, user }"],
        ["label(): string",
         "badgeClasses(): string",
         "isVisibleOnSite(): bool",
         "options(): array"],
        color=PALETTE["cyan"])

    # association lines
    edge = PALETTE["accent"]
    d.path("M440 200 C 460 200, 470 240, 480 240", color=edge, sw=2,
           marker_end="arrow-accent")
    d.text(458, 224, "1—∗", size=11, fill=PALETTE["ink_dim"])
    d.path("M900 200 C 920 200, 920 200, 940 180", color=edge, sw=1.6,
           marker_end="arrow-accent")
    d.path("M900 220 C 920 230, 1200 200, 1230 200", color=edge, sw=1.6,
           marker_end="arrow-accent")
    d.path("M900 440 C 920 440, 920 440, 940 440", color=edge, sw=1.6,
           marker_end="arrow-accent")
    d.path("M1210 440 C 1230 440, 1220 440, 1230 440", color=edge, sw=1.6,
           marker_end="arrow-accent")
    d.path("M250 450 C 250 460, 250 480, 250 500", color=edge, sw=1.6,
           marker_end="arrow-accent")
    d.path("M440 580 C 470 580, 480 720, 480 750", color=edge, sw=1.6,
           marker_end="arrow-accent")
    return d


# ---------- 08. Use case diagram ----------
def diagram_08_usecase() -> SVG:
    d = SVG(1400, 950, "08 — Use Case Diagram")

    # actors
    d.actor(100, 200, "Guest")
    d.actor(100, 420, "Buyer")
    d.actor(100, 640, "Agent")
    d.actor(100, 840, "Admin")

    # system boundary
    d.rect(280, 90, 1080, 820, fill=PALETTE["surface"],
           stroke=PALETTE["accent2"], sw=2, dash="6 6")
    d.text(820, 116, "Estatify", size=15, weight=700, fill=PALETTE["accent2"])

    use_cases = [
        # (x, y, label, actors)
        (390, 170, "Browse properties", [200, 420, 640, 840]),
        (610, 170, "Search & filter", [200, 420, 640, 840]),
        (830, 170, "View property detail", [200, 420, 640, 840]),
        (1060, 170, "Compare properties", [200, 420, 640, 840]),
        (390, 270, "EMI calculator", [200, 420, 640, 840]),
        (610, 270, "Investment calculator", [200, 420, 640, 840]),
        (830, 270, "QR / verify property", [200, 420, 640, 840]),
        (1060, 270, "Download property PDF", [200, 420, 640]),
        (390, 380, "Register / log in", [200, 420, 640]),
        (610, 380, "Manage wishlist", [420]),
        (830, 380, "Book appointment", [420]),
        (1060, 380, "Download receipt PDF", [420]),
        (390, 490, "Chat with agent", [420]),
        (610, 490, "Receive notifications", [420, 640]),
        (830, 490, "Reply to enquiries", [640]),
        (1060, 490, "Manage own listings", [640]),
        (390, 600, "Create / edit property", [640]),
        (610, 600, "Upload media & docs", [640]),
        (830, 600, "Smart price predict (demo)", [640]),
        (1060, 600, "Confirm / cancel visits", [640]),
        (390, 720, "Admin login", [840]),
        (610, 720, "Approve / reject property", [840]),
        (830, 720, "Mark under review", [840]),
        (1060, 720, "Activate / deactivate user", [840]),
        (500, 830, "View moderation dashboard", [840]),
        (820, 830, "Manage user roles", [840]),
    ]

    for x, y, lbl, _ in use_cases:
        d.add(f'<ellipse cx="{x}" cy="{y}" rx="105" ry="30"'
              f' fill="{PALETTE["panel"]}" stroke="{PALETTE["accent"]}"'
              f' stroke-width="1.6"/>')
        d.text(x, y + 4, lbl, size=11, fill=PALETTE["ink"])

    for x, y, _, actors in use_cases:
        for ay in actors:
            d.path(f"M140 {ay} C 220 {ay}, {x-110} {y}, {x-105} {y}",
                   color=PALETTE["edge"], sw=0.9, marker_end=None, dash="2 4")
    return d


# ---------- 09. Sequence — submit property ----------
def diagram_09_sequence_submit() -> SVG:
    d = SVG(1500, 900, "09 — Sequence: Submit a property for review")

    lanes = ["Agent\n(Browser)", "Routes", "PropertyController",
             "PropertyRequest", "StorePropertyAction",
             "PropertyMediaService", "DB / Storage"]
    n = len(lanes)
    margin = 80
    lane_w = (1500 - 2 * margin) / n
    top = 110
    bottom = 820
    for i, lane in enumerate(lanes):
        x = margin + i * lane_w + lane_w / 2
        d.rect(x - lane_w / 2 + 14, top - 40, lane_w - 28, 50,
               fill=PALETTE["panel"], stroke=PALETTE["accent"], sw=1.4)
        for j, l in enumerate(lane.split("\n")):
            d.text(x, top - 20 + j * 14, l, size=12, weight=600)
        d.line(x, top + 10, x, bottom, color=PALETTE["edge"], dash="3 4")

    def x_of(idx):
        return margin + idx * lane_w + lane_w / 2

    def msg(y, src, dst, text, color=PALETTE["accent"], dash=None):
        x1, x2 = x_of(src), x_of(dst)
        d.path(f"M{x1} {y} L{x2} {y}", color=color, sw=1.8,
               marker_end="arrow-accent" if color == PALETTE["accent"] else "arrow-emerald",
               dash=dash)
        d.text((x1 + x2) / 2, y - 6, text, size=11, fill=PALETTE["ink_dim"])

    def note(y, idx, text):
        x = x_of(idx)
        d.rect(x - 110, y - 14, 220, 28, fill=PALETTE["warn"], stroke="none",
               opacity=0.18, rx=6)
        d.text(x, y + 5, text, size=11, fill=PALETTE["warn"])

    y = 150
    msg(y, 0, 1, "POST /properties (multipart)"); y += 40
    msg(y, 1, 2, "→ PropertyController@store"); y += 40
    msg(y, 2, 3, "validate(rules)"); y += 30
    msg(y, 3, 2, "validated data", color=PALETTE["accent2"], dash="4 4"); y += 40
    msg(y, 2, 4, "execute(ownerId, data, files)"); y += 40
    note(y, 4, "DB::transaction { … }"); y += 38
    msg(y, 4, 6, "INSERT properties (slug, approval_status=submitted)"); y += 40
    msg(y, 4, 5, "syncFromUpload(property, files)"); y += 30
    msg(y, 5, 6, "store images / video / docs on public disk"); y += 30
    msg(y, 5, 6, "INSERT property_{images, videos, documents}"); y += 40
    msg(y, 5, 4, "ok", color=PALETTE["accent2"], dash="4 4"); y += 40
    msg(y, 4, 2, "Property", color=PALETTE["accent2"], dash="4 4"); y += 40
    msg(y, 2, 0, "302 → /properties/{slug}  (flash: submitted for review)",
        color=PALETTE["accent2"], dash="4 4"); y += 40
    return d


# ---------- 10. Sequence — chat send ----------
def diagram_10_sequence_chat() -> SVG:
    d = SVG(1400, 820, "10 — Sequence: Buyer messages an agent")

    lanes = ["Buyer", "Routes", "ChatController", "Conversation",
             "Message", "NewMessageNotification", "Agent"]
    n = len(lanes)
    margin = 80
    lane_w = (1400 - 2 * margin) / n
    top = 110
    bottom = 760

    for i, lane in enumerate(lanes):
        x = margin + i * lane_w + lane_w / 2
        d.rect(x - lane_w / 2 + 14, top - 40, lane_w - 28, 50,
               fill=PALETTE["panel"], stroke=PALETTE["accent"], sw=1.4)
        d.text(x, top - 12, lane, size=12, weight=600)
        d.line(x, top + 10, x, bottom, color=PALETTE["edge"], dash="3 4")

    def x_of(i):
        return margin + i * lane_w + lane_w / 2

    def msg(y, s, dst, text, color=PALETTE["accent"], dash=None):
        x1, x2 = x_of(s), x_of(dst)
        d.path(f"M{x1} {y} L{x2} {y}", color=color, sw=1.8,
               marker_end="arrow-accent" if color == PALETTE["accent"]
               else "arrow-emerald", dash=dash)
        d.text((x1 + x2) / 2, y - 6, text, size=11, fill=PALETTE["ink_dim"])

    y = 150
    msg(y, 0, 1, "POST /messages/start/{property}"); y += 36
    msg(y, 1, 2, "ChatController@start"); y += 36
    msg(y, 2, 3, "firstOrCreate(prop, buyer, agent)"); y += 36
    msg(y, 3, 2, "Conversation", color=PALETTE["accent2"], dash="4 4"); y += 50

    msg(y, 0, 1, "POST /messages/{conversation}"); y += 36
    msg(y, 1, 2, "ChatController@send"); y += 36
    msg(y, 2, 4, "Message::create(sender, body)"); y += 36
    msg(y, 2, 3, "touch(last_message_at)"); y += 50

    msg(y, 2, 5, "notify(NewMessageNotification)"); y += 36
    msg(y, 5, 6, "DB notification row + (mail relay)",
        color=PALETTE["accent2"]); y += 50
    msg(y, 2, 0, "redirect → conversation view",
        color=PALETTE["accent2"], dash="4 4"); y += 36
    return d


# ---------- 11. State diagram — property approval ----------
def diagram_11_state() -> SVG:
    d = SVG(1300, 700, "11 — State diagram: Property approval lifecycle")

    states = [
        ("Draft", 200, 250, PALETTE["ink_dim"]),
        ("Submitted", 470, 250, PALETTE["warn"]),
        ("Under review", 740, 250, PALETTE["violet"]),
        ("Approved", 1050, 150, PALETTE["accent2"]),
        ("Rejected", 1050, 380, PALETTE["danger"]),
        ("Soft-deleted", 470, 530, PALETTE["edge"]),
    ]
    for label, x, y, c in states:
        d.add(f'<rect x="{x-110}" y="{y-40}" width="220" height="80" rx="14"'
              f' fill="{PALETTE["surface"]}" stroke="{c}" stroke-width="2.5"/>')
        d.text(x, y + 6, label, size=15, weight=700, fill=c)

    # start dot
    d.add(f'<circle cx="55" cy="250" r="10" fill="{PALETTE["accent"]}"/>')
    d.path("M65 250 L 90 250",
           color=PALETTE["accent"], marker_end="arrow-accent", sw=2)
    d.text(77, 240, "save()", size=11, fill=PALETTE["ink_dim"])

    # draft → submitted
    d.path("M310 250 L 360 250", color=PALETTE["edge"], marker_end="arrow", sw=2)
    d.text(335, 240, "StorePropertyAction (default → submitted)",
           size=10, fill=PALETTE["ink_dim"])

    # submitted → under_review
    d.path("M580 250 L 630 250", color=PALETTE["edge"], marker_end="arrow", sw=2)
    d.text(605, 240, "Admin: mark under review",
           size=10, fill=PALETTE["ink_dim"])

    # submitted → approved (direct)
    d.path("M580 230 C 700 180, 850 150, 940 150",
           color=PALETTE["edge"], marker_end="arrow", sw=2)
    d.text(770, 130, "Admin: approve (sets approved_at)",
           size=10, fill=PALETTE["ink_dim"])

    # under_review → approved
    d.path("M850 230 L 950 170", color=PALETTE["edge"], marker_end="arrow", sw=2)
    # under_review → rejected
    d.path("M850 270 L 950 380", color=PALETTE["edge"], marker_end="arrow", sw=2)
    d.text(880, 320, "reject (with reason)",
           size=10, fill=PALETTE["ink_dim"])

    # rejected → submitted (resubmit)
    d.path("M1050 420 C 720 470, 580 360, 480 290",
           color=PALETTE["edge"], marker_end="arrow", sw=2, dash="4 4")
    d.text(720, 470, "Agent edits & resubmits", size=10,
           fill=PALETTE["ink_dim"])

    # any → soft delete
    d.path("M470 290 L 470 490", color=PALETTE["danger"],
           marker_end="arrow", sw=2, dash="3 5")
    d.text(360, 410, "Owner / Admin delete (SoftDeletes)",
           size=10, fill=PALETTE["ink_dim"])

    # legend
    d.text(640, 640, "Only Approved is visible via Property::scopeVisible() — "
           "every other state is hidden from the public listing.",
           size=12, italic=True, fill=PALETTE["ink_dim"])
    return d


# ---------- 12. Request lifecycle ----------
def diagram_12_request_lifecycle() -> SVG:
    d = SVG(1500, 700, "12 — HTTP Request Lifecycle (Laravel pipeline)")

    steps = [
        ("Browser request", PALETTE["accent"]),
        ("public/index.php\nfront controller", PALETTE["accent"]),
        ("Kernel\nHandle()", PALETTE["accent2"]),
        ("Global middleware\n(StartSession, VerifyCsrfToken, …)",
         PALETTE["accent2"]),
        ("Route resolution\nweb.php", PALETTE["violet"]),
        ("Route middleware\nauth · admin · guest", PALETTE["violet"]),
        ("FormRequest\nvalidate()", PALETTE["warn"]),
        ("Controller\naction", PALETTE["warn"]),
        ("Action / Service\n(Eloquent + Storage)", PALETTE["pink"]),
        ("View / Redirect / JSON", PALETTE["pink"]),
        ("Response\nrendered HTML", PALETTE["accent"]),
    ]
    y = 220
    box_w, box_h, gap = 140, 90, 2
    total = len(steps) * (box_w + gap) - gap
    x0 = (1500 - total) / 2
    for i, (label, c) in enumerate(steps):
        x = x0 + i * (box_w + gap)
        d.rect(x, y, box_w, box_h, fill=PALETTE["surface"], stroke=c, sw=2)
        for j, ln in enumerate(label.split("\n")):
            d.text(x + box_w / 2, y + 36 + j * 16, ln, size=11,
                   weight=600 if j == 0 else 400,
                   fill=c if j == 0 else PALETTE["ink"])
        if i < len(steps) - 1:
            d.path(f"M{x + box_w} {y + box_h/2} L {x + box_w + gap} {y + box_h/2}",
                   color=PALETTE["edge"], sw=1.6, marker_end="arrow")

    # response path back
    d.text(750, 380, "Each step in the pipeline can short-circuit and "
           "return a Response (401, 403, 422 …).",
           italic=True, fill=PALETTE["ink_dim"], size=12)
    d.path(f"M{x0 + total - box_w/2} {y + box_h + 10} C {x0 + total - box_w/2} "
           f"460, {x0 + box_w/2} 460, {x0 + box_w/2} {y + box_h + 10}",
           color=PALETTE["accent2"], sw=2.2, dash="5 5",
           marker_end="arrow-emerald")
    d.text(750, 470, "Response returned to browser",
           fill=PALETTE["accent2"], size=12, weight=600)
    return d


# ---------- 13. Deployment diagram ----------
def diagram_13_deployment() -> SVG:
    d = SVG(1400, 850, "13 — Deployment Diagram (single-node baseline)")

    # client node
    d.rect(60, 120, 280, 280, fill=PALETTE["surface"],
           stroke=PALETTE["accent"], sw=2)
    d.text(200, 152, "End-user device", size=14, weight=700,
           fill=PALETTE["accent"])
    d.text(200, 174, "(desktop / mobile)", size=11, italic=True,
           fill=PALETTE["ink_dim"])
    d.card(80, 200, 240, 80, "Browser", subtitle="Blade · Alpine · Tailwind",
           accent=PALETTE["accent"])
    d.card(80, 300, 240, 80, "Mobile camera", subtitle="QR scanner → /verify",
           accent=PALETTE["accent"])

    # app server
    d.rect(460, 60, 540, 720, fill=PALETTE["surface"],
           stroke=PALETTE["accent2"], sw=2)
    d.text(730, 92, "Application server", size=14, weight=700,
           fill=PALETTE["accent2"])
    d.text(730, 114, "(php artisan serve in dev · php-fpm/nginx in prod)",
           size=11, italic=True, fill=PALETTE["ink_dim"])

    d.card(480, 140, 500, 80, "Nginx / Caddy",
           subtitle="reverse proxy → PHP-FPM (optional in dev)",
           accent=PALETTE["accent"])
    d.card(480, 235, 500, 90, "PHP-FPM 8.3",
           subtitle="Laravel 13 framework",
           body=["public/index.php as entrypoint"],
           accent=PALETTE["accent2"])
    d.card(480, 340, 500, 100, "Estatify app",
           subtitle="routes / controllers / services / models",
           body=["composer autoloaded App\\* namespace"],
           accent=PALETTE["violet"])
    d.card(480, 455, 240, 90, "Queue worker",
           subtitle="php artisan queue:work",
           accent=PALETTE["warn"])
    d.card(740, 455, 240, 90, "Scheduler",
           subtitle="php artisan schedule:run (cron)",
           accent=PALETTE["warn"])
    d.card(480, 560, 500, 90, "Vite (dev only)",
           subtitle="npm run dev → HMR over ws",
           accent=PALETTE["cyan"])
    d.card(480, 665, 500, 80, "storage/app/public",
           subtitle="property/images · videos · docs",
           accent=PALETTE["pink"])

    # data node
    d.rect(1060, 120, 280, 660, fill=PALETTE["surface"],
           stroke=PALETTE["warn"], sw=2)
    d.text(1200, 152, "Data node", size=14, weight=700, fill=PALETTE["warn"])
    d.text(1200, 174, "co-located (SQLite) or remote (MySQL)",
           size=11, italic=True, fill=PALETTE["ink_dim"])
    d.db_cylinder(1090, 200, 220, 120, "Primary DB",
                  sub="SQLite / MySQL")
    d.db_cylinder(1090, 340, 220, 110, "Sessions", sub="DB driver",
                  color=PALETTE["accent"])
    d.db_cylinder(1090, 470, 220, 110, "Queue + Cache",
                  sub="DB driver", color=PALETTE["accent"])
    d.db_cylinder(1090, 600, 220, 130, "Notifications",
                  sub="uuid + morph", color=PALETTE["violet"])

    # external boxes
    d.cloud(60, 470, 280, 110, "SMTP / mail log relay")
    d.cloud(60, 600, 280, 110, "Gravatar (CDN)")

    # arrows
    d.path("M340 240 C 400 240, 420 180, 480 180", color=PALETTE["accent"],
           sw=2, marker_end="arrow-accent")
    d.text(420, 200, "HTTPS / 443", size=11, fill=PALETTE["ink_dim"])
    d.path("M340 340 C 400 340, 420 280, 480 280", color=PALETTE["accent"],
           sw=2, marker_end="arrow-accent", dash="4 4")

    d.path("M980 280 C 1020 280, 1040 250, 1090 250",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")
    d.text(1030, 240, "PDO", size=11, fill=PALETTE["ink_dim"])
    d.path("M980 500 C 1020 500, 1040 400, 1090 400",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")
    d.path("M980 500 C 1020 510, 1040 520, 1090 525",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")
    d.path("M980 660 C 1020 660, 1040 665, 1090 665",
           color=PALETTE["accent2"], sw=2, marker_end="arrow-emerald")

    d.path("M480 530 C 360 540, 340 525, 340 525", color=PALETTE["edge"],
           sw=1.6, marker_end="arrow", dash="4 4")
    d.text(390, 510, "queued mail", size=11, fill=PALETTE["ink_dim"])
    d.path("M340 655 C 400 655, 420 380, 480 380", color=PALETTE["edge"],
           sw=1.4, dash="3 5", marker_end="arrow")
    d.text(390, 700, "avatar URL", size=11, fill=PALETTE["ink_dim"])
    return d


# ---------- entrypoint ----------
DIAGRAMS = [
    ("01-system-context",       diagram_01_system_context),
    ("02-containers",            diagram_02_containers),
    ("03-components",            diagram_03_components),
    ("04-layered-architecture",  diagram_04_layered),
    ("05-package-map",           diagram_05_packages),
    ("06-er-diagram",            diagram_06_er),
    ("07-class-diagram",         diagram_07_class),
    ("08-use-case-diagram",      diagram_08_usecase),
    ("09-sequence-submit-property", diagram_09_sequence_submit),
    ("10-sequence-chat",         diagram_10_sequence_chat),
    ("11-state-property-approval", diagram_11_state),
    ("12-request-lifecycle",     diagram_12_request_lifecycle),
    ("13-deployment",            diagram_13_deployment),
]


def main() -> None:
    for slug, fn in DIAGRAMS:
        path = os.path.join(OUT, f"{slug}.svg")
        fn().save(path)
        print(f"wrote {path}")


if __name__ == "__main__":
    main()
