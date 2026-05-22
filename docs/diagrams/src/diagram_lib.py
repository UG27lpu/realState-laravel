"""Tiny SVG primitives for the Estatify architecture diagrams.

Pure-Python — no external deps. Renders to SVG strings that are written to
disk and then converted to PNG via rsvg-convert.
"""
from __future__ import annotations

from dataclasses import dataclass, field
from html import escape
from typing import Iterable

# ---------- palette ----------
PALETTE = {
    "bg":          "#0f172a",   # slate-900
    "surface":     "#111827",   # gray-900
    "panel":       "#1f2937",   # gray-800
    "ink":         "#e5e7eb",   # gray-200
    "ink_dim":     "#9ca3af",   # gray-400
    "accent":      "#60a5fa",   # blue-400
    "accent2":     "#34d399",   # emerald-400
    "warn":        "#fbbf24",   # amber-400
    "danger":      "#f87171",   # red-400
    "violet":      "#a78bfa",
    "pink":        "#f472b6",
    "cyan":        "#22d3ee",
    "muted":       "#374151",
    "edge":        "#4b5563",
}

FONT = "ui-sans-serif, -apple-system, Segoe UI, Roboto, sans-serif"
MONO = "ui-monospace, SFMono-Regular, Menlo, monospace"


def _wrap(text: str, max_chars: int) -> list[str]:
    words = text.split()
    if not words:
        return [""]
    lines, line = [], ""
    for w in words:
        if not line:
            line = w
        elif len(line) + 1 + len(w) <= max_chars:
            line += " " + w
        else:
            lines.append(line)
            line = w
    lines.append(line)
    return lines


@dataclass
class SVG:
    width: int
    height: int
    title: str = ""
    parts: list[str] = field(default_factory=list)

    def add(self, raw: str) -> None:
        self.parts.append(raw)

    # ----- primitives -----
    def rect(self, x, y, w, h, fill=PALETTE["panel"], stroke=PALETTE["edge"],
             rx=10, sw=1.5, dash=None, opacity=1.0):
        d = f' stroke-dasharray="{dash}"' if dash else ""
        self.add(
            f'<rect x="{x}" y="{y}" width="{w}" height="{h}" rx="{rx}" ry="{rx}"'
            f' fill="{fill}" stroke="{stroke}" stroke-width="{sw}"{d} opacity="{opacity}"/>'
        )

    def line(self, x1, y1, x2, y2, color=PALETTE["edge"], sw=1.4, dash=None,
             marker_end="arrow", marker_start=None):
        d = f' stroke-dasharray="{dash}"' if dash else ""
        me = f' marker-end="url(#{marker_end})"' if marker_end else ""
        ms = f' marker-start="url(#{marker_start})"' if marker_start else ""
        self.add(
            f'<line x1="{x1}" y1="{y1}" x2="{x2}" y2="{y2}" stroke="{color}"'
            f' stroke-width="{sw}"{d}{me}{ms}/>'
        )

    def path(self, d, color=PALETTE["edge"], sw=1.4, dash=None, fill="none",
             marker_end="arrow"):
        ds = f' stroke-dasharray="{dash}"' if dash else ""
        me = f' marker-end="url(#{marker_end})"' if marker_end else ""
        self.add(
            f'<path d="{d}" fill="{fill}" stroke="{color}" stroke-width="{sw}"{ds}{me}/>'
        )

    def text(self, x, y, s, *, fill=PALETTE["ink"], size=13, weight=400,
             anchor="middle", family=FONT, italic=False):
        st = ' font-style="italic"' if italic else ""
        self.add(
            f'<text x="{x}" y="{y}" fill="{fill}" font-family="{family}"'
            f' font-size="{size}" font-weight="{weight}" text-anchor="{anchor}"{st}>'
            f'{escape(s)}</text>'
        )

    def multiline(self, x, y, lines: Iterable[str], *, fill=PALETTE["ink"],
                  size=12, weight=400, anchor="middle", line_height=15,
                  family=FONT):
        for i, ln in enumerate(lines):
            self.text(x, y + i * line_height, ln, fill=fill, size=size,
                      weight=weight, anchor=anchor, family=family)

    # ----- higher-level helpers -----
    def card(self, x, y, w, h, title, *, subtitle=None, body=None,
             fill=PALETTE["panel"], stroke=PALETTE["edge"], accent=None,
             title_size=14, body_size=11):
        self.rect(x, y, w, h, fill=fill, stroke=stroke)
        if accent:
            self.rect(x, y, 6, h, fill=accent, stroke="none", rx=3)
        cy = y + 22
        self.text(x + w / 2, cy, title, fill=PALETTE["ink"], size=title_size,
                  weight=600)
        if subtitle:
            self.text(x + w / 2, cy + 18, subtitle, fill=PALETTE["ink_dim"],
                      size=11, italic=True)
            cy += 18
        if body:
            for i, ln in enumerate(body):
                self.text(x + w / 2, cy + 22 + i * 15, ln,
                          fill=PALETTE["ink"], size=body_size)

    def actor(self, x, y, label, *, color=PALETTE["accent"]):
        """Stick figure used in use-case / context diagrams."""
        self.add(
            f'<circle cx="{x}" cy="{y - 28}" r="10" fill="none"'
            f' stroke="{color}" stroke-width="2"/>'
        )
        self.add(
            f'<path d="M{x} {y - 18} L{x} {y + 6} M{x - 14} {y - 8}'
            f' L{x + 14} {y - 8} M{x} {y + 6} L{x - 12} {y + 24}'
            f' M{x} {y + 6} L{x + 12} {y + 24}" fill="none"'
            f' stroke="{color}" stroke-width="2" stroke-linecap="round"/>'
        )
        self.text(x, y + 42, label, fill=PALETTE["ink"], size=12, weight=600)

    def db_cylinder(self, x, y, w, h, label, sub=None, color=PALETTE["accent2"]):
        rx = w / 2
        ry = 14
        top = f"M{x} {y + ry} A{rx} {ry} 0 0 1 {x + w} {y + ry}"
        bot = (
            f"L{x + w} {y + h - ry} A{rx} {ry} 0 0 1 {x} {y + h - ry} Z"
        )
        self.add(
            f'<path d="{top} {bot}" fill="{PALETTE["surface"]}" stroke="{color}"'
            f' stroke-width="2"/>'
        )
        self.add(
            f'<path d="M{x} {y + ry} A{rx} {ry} 0 0 0 {x + w} {y + ry}"'
            f' fill="none" stroke="{color}" stroke-width="2"/>'
        )
        cy = y + h / 2 + 4
        self.text(x + w / 2, cy, label, fill=PALETTE["ink"], size=13, weight=600)
        if sub:
            self.text(x + w / 2, cy + 16, sub, fill=PALETTE["ink_dim"], size=11)

    def cloud(self, x, y, w, h, label, color=PALETTE["violet"]):
        # decorative rounded cloud-ish blob using overlapping ellipses
        cx, cy = x + w / 2, y + h / 2
        self.add(f'<g fill="{PALETTE["surface"]}" stroke="{color}" stroke-width="2">')
        self.add(f'<ellipse cx="{cx}" cy="{cy}" rx="{w/2}" ry="{h/2}"/>')
        self.add(f'<ellipse cx="{cx - w*0.3}" cy="{cy + h*0.05}" rx="{w*0.28}" ry="{h*0.42}"/>')
        self.add(f'<ellipse cx="{cx + w*0.3}" cy="{cy + h*0.05}" rx="{w*0.28}" ry="{h*0.42}"/>')
        self.add(f'<ellipse cx="{cx}" cy="{cy - h*0.25}" rx="{w*0.32}" ry="{h*0.32}"/>')
        self.add('</g>')
        self.text(cx, cy + 4, label, fill=PALETTE["ink"], size=12, weight=600)

    def section_header(self, x, y, w, label, color=PALETTE["accent"]):
        self.add(
            f'<rect x="{x}" y="{y}" width="{w}" height="26" rx="8" ry="8"'
            f' fill="{color}" opacity="0.18" stroke="{color}" stroke-width="1"/>'
        )
        self.text(x + 14, y + 18, label, fill=color, size=12, weight=700,
                  anchor="start")

    # ----- serialization -----
    def render(self) -> str:
        defs = f"""
        <defs>
          <marker id="arrow" viewBox="0 0 10 10" refX="9" refY="5"
                  markerWidth="9" markerHeight="9" orient="auto-start-reverse">
            <path d="M0,0 L10,5 L0,10 z" fill="{PALETTE['edge']}"/>
          </marker>
          <marker id="arrow-accent" viewBox="0 0 10 10" refX="9" refY="5"
                  markerWidth="9" markerHeight="9" orient="auto-start-reverse">
            <path d="M0,0 L10,5 L0,10 z" fill="{PALETTE['accent']}"/>
          </marker>
          <marker id="arrow-emerald" viewBox="0 0 10 10" refX="9" refY="5"
                  markerWidth="9" markerHeight="9" orient="auto-start-reverse">
            <path d="M0,0 L10,5 L0,10 z" fill="{PALETTE['accent2']}"/>
          </marker>
          <marker id="dot" viewBox="0 0 10 10" refX="5" refY="5"
                  markerWidth="8" markerHeight="8">
            <circle cx="5" cy="5" r="4" fill="{PALETTE['accent']}"/>
          </marker>
        </defs>
        """
        title = ""
        if self.title:
            self.text(self.width / 2, 36, self.title, size=20, weight=700,
                      fill=PALETTE["ink"])
        body = "\n".join(self.parts)
        return f"""<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {self.width} {self.height}"
     width="{self.width}" height="{self.height}">
  <rect width="100%" height="100%" fill="{PALETTE['bg']}"/>
  {defs}
  {title}
  {body}
</svg>
"""

    def save(self, path: str) -> None:
        with open(path, "w", encoding="utf-8") as f:
            f.write(self.render())
