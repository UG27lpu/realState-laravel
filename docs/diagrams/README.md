# Diagrams

Source-of-truth catalogue lives in [`../architecture.md`](../architecture.md).

| # | Diagram                          | PNG | SVG |
| - | -------------------------------- | --- | --- |
| 01 | System context (C4 L1)          | [png](png/01-system-context.png) | [svg](svg/01-system-context.svg) |
| 02 | Containers (C4 L2)              | [png](png/02-containers.png) | [svg](svg/02-containers.svg) |
| 03 | Components (C4 L3)              | [png](png/03-components.png) | [svg](svg/03-components.svg) |
| 04 | Layered architecture            | [png](png/04-layered-architecture.png) | [svg](svg/04-layered-architecture.svg) |
| 05 | Package / module map            | [png](png/05-package-map.png) | [svg](svg/05-package-map.svg) |
| 06 | ER diagram                      | [png](png/06-er-diagram.png) | [svg](svg/06-er-diagram.svg) |
| 07 | Eloquent class diagram          | [png](png/07-class-diagram.png) | [svg](svg/07-class-diagram.svg) |
| 08 | Use case diagram                | [png](png/08-use-case-diagram.png) | [svg](svg/08-use-case-diagram.svg) |
| 09 | Sequence — submit property      | [png](png/09-sequence-submit-property.png) | [svg](svg/09-sequence-submit-property.svg) |
| 10 | Sequence — chat                 | [png](png/10-sequence-chat.png) | [svg](svg/10-sequence-chat.svg) |
| 11 | State — property approval       | [png](png/11-state-property-approval.png) | [svg](svg/11-state-property-approval.svg) |
| 12 | Request lifecycle               | [png](png/12-request-lifecycle.png) | [svg](svg/12-request-lifecycle.svg) |
| 13 | Deployment                      | [png](png/13-deployment.png) | [svg](svg/13-deployment.svg) |

## Regenerating

```bash
python3 docs/diagrams/src/generate.py
for f in docs/diagrams/svg/*.svg; do
  rsvg-convert -z 2 -o "docs/diagrams/png/$(basename "$f" .svg).png" "$f"
done
```

No external Python packages are required — only `rsvg-convert` (from
`librsvg`) is needed at the shell level.
