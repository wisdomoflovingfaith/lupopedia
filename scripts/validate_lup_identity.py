#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.2.11"
#   file_path_from_root: "scripts/validate_lup_identity.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/validate_lup_identity.py"
#   status: "active"
#   when_updated: "20260814140129"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: "0/development/validate-lup-identity"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   default_collection_id: null
#   lupopedia.schema: implementation
#   prd_cluster: 16_C_16_E_99_A
#   title: "LUP identity validator (header 4.2.11 KEY + 4.2.4 hyphen dual-accept)"
#   summary: "Accept LUP.KEY dotted identity and lupopedia.map; dual-accept 4.2.4 hyphen LUP."
# ---------------------------------------------------------------------
"""Validate lupopedia.identity against PRD 16_C section 4.2.5 (header 4.2.4).

Federation Compression Rule (Option A): human-friendly forms may use X for
federation 000001. Machine/canonical storage remains six-hex 000001.
Validators expand X -> 000001 internally. X means only 000001.

Does not validate the dense 28-field header grid. Use
validate_lupopedia_headers_universal.py for that surface.

Usage:
  python scripts/validate_lup_identity.py PATH [PATH ...]
  python scripts/validate_lup_identity.py --migration PATH
"""

from __future__ import print_function

import argparse
import os
import re
import sys

ISO_639_1 = set(
    [
        "AA", "AB", "AE", "AF", "AK", "AM", "AN", "AR", "AS", "AV", "AY", "AZ",
        "BA", "BE", "BG", "BH", "BI", "BM", "BN", "BO", "BR", "BS",
        "CA", "CE", "CH", "CO", "CR", "CS", "CU", "CV", "CY",
        "DA", "DE", "DV", "DZ",
        "EE", "EL", "EN", "EO", "ES", "ET", "EU",
        "FA", "FF", "FI", "FJ", "FO", "FR", "FY",
        "GA", "GD", "GL", "GN", "GU", "GV",
        "HA", "HE", "HI", "HO", "HR", "HT", "HU", "HY", "HZ",
        "IA", "ID", "IE", "IG", "II", "IK", "IO", "IS", "IT", "IU",
        "JA", "JV",
        "KA", "KG", "KI", "KJ", "KK", "KL", "KM", "KN", "KO", "KR", "KS", "KU",
        "KV", "KW", "KY",
        "LA", "LB", "LG", "LI", "LN", "LO", "LT", "LU", "LV",
        "MG", "MH", "MI", "MK", "ML", "MN", "MR", "MS", "MT", "MY",
        "NA", "NB", "ND", "NE", "NG", "NL", "NN", "NO", "NR", "NV", "NY",
        "OC", "OJ", "OM", "OR", "OS",
        "PA", "PI", "PL", "PS", "PT",
        "QU",
        "RM", "RN", "RO", "RU", "RW",
        "SA", "SC", "SD", "SE", "SG", "SI", "SK", "SL", "SM", "SN", "SO", "SQ",
        "SR", "SS", "ST", "SU", "SV", "SW",
        "TA", "TE", "TG", "TH", "TI", "TK", "TL", "TN", "TO", "TR", "TS", "TT",
        "TW", "TY",
        "UG", "UK", "UR", "UZ",
        "VE", "VI", "VO",
        "WA", "WO",
        "XH",
        "YI", "YO",
        "ZA", "ZH", "ZU",
    ]
)

# Reserved LL. Not ISO 639-1. Multi-language / language-agnostic.
# Never add ZZ to ISO_639_1.
LL_MULTILANG = "ZZ"


def is_iso_639_1(ll):
    if ll == LL_MULTILANG:
        return False
    return ll in ISO_639_1


def is_allowed_ll(ll):
    return ll == LL_MULTILANG or is_iso_639_1(ll)


HEX2 = re.compile(r"^[0-9A-F]{2}$")
HEX6 = re.compile(r"^[0-9A-F]{6}$")
LETTERS2 = re.compile(r"^[A-Z]{2}$")
# Official lineage delimiter inside RRRRRR. Not hex. Not used elsewhere.
LINEAGE_DELIM = ":"
HFV_RE = re.compile(r'header_format_version:\s*["\']?([0-9.]+)')
ID_RE = re.compile(r'lupopedia_id:\s*["\']([^"\']+)["\']')
KEY_RE = re.compile(
    r"^\s*(federation_id|color_hex|namespace_id|group_id|actor_aa|language|iteration|artifact_hex|origin_federation_id|LUPOPEDIA|LUP\.KEY|LUP\.HEX|LUP\.SHORT|LUP\.ROOT|LUP\.OMIT|LUP\.DEFAULTS|index):\s*[\"']?([^\"'\s]+)"
)

LUP_KEY_CANONICAL = "PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION"
FORBIDDEN_DELIM_RE = re.compile(r"[|\-]")

# Federation Compression Rule (Option A) -- header 4.2.4
ROOT_FEDERATION_ID = "000001"
FED_COMPRESS_SYMBOL = "X"


def normalize_federation_token(ff):
    """Return (machine_ff, used_x, error_message).

    X is lossless compression of 000001 only. All other federations stay 6-hex.
    """
    if ff is None:
        return (None, False, "missing federation token")
    token = ff.upper()
    if token == FED_COMPRESS_SYMBOL:
        return (ROOT_FEDERATION_ID, True, "")
    if HEX6.match(token):
        return (token, False, "")
    return (None, False, "federation token %s is not 6-hex or X" % ff)


def parse_artifact_token(rr):
    """Parse RRRRRR. Return (origin_ff_or_none, artifact_number, machine_token, error).

    Native: six hex, no colon -- artifact is native to the current federation.
    Lineage: originFederation:artifactNumber. Split on the first colon only.
    Colon is the only lineage delimiter. X inside RRRRRR is origin compression
    of 000001 on the left of the colon, never a delimiter.
    """
    if rr is None:
        return (None, None, None, "missing artifact token")
    token = rr.upper()
    if LINEAGE_DELIM in token:
        if token.count(LINEAGE_DELIM) != 1:
            return (None, None, None, "HDR_LUP_COLON_ELSEWHERE: colon only once in RRRRRR")
        left, right = token.split(LINEAGE_DELIM, 1)
        origin, _used_x, err = normalize_federation_token(left)
        if err or origin is None:
            return (None, None, None, "HDR_LUP_RR_ORIGIN: origin federation %s invalid" % left)
        if origin in ("000000", "FFFFFF"):
            return (None, None, None, "HDR_LUP_FF_RESERVED: origin federation %s reserved" % origin)
        if not HEX6.match(right):
            return (None, None, None, "HDR_LUP_RR_RANGE: artifact number %s is not 6 hex" % right)
        machine = "%s%s%s" % (origin, LINEAGE_DELIM, right)
        return (origin, right, machine, "")
    if re.search(r"[^0-9A-F]", token):
        return (
            None,
            None,
            None,
            "HDR_LUP_RR_LEGACY_DELIM: lineage delimiter must be colon, got %s" % rr,
        )
    if not HEX6.match(token):
        return (None, None, None, "HDR_LUP_RR_RANGE: artifact_hex %s is not 6 hex" % token)
    return (None, token, token, "")


def is_artifact_token(rr):
    _origin, _num, _machine, err = parse_artifact_token(rr)
    return err == ""


def human_friendly_federation(ff):
    """Compress 000001 -> X for human-friendly / short-form output only."""
    machine, _used_x, err = normalize_federation_token(ff)
    if err or machine is None:
        return ff
    if machine == ROOT_FEDERATION_ID:
        return FED_COMPRESS_SYMBOL
    return machine


def human_friendly_string(tok, n_tokens=6):
    """Build human-friendly LUP string. Compression applies only here."""
    ff = human_friendly_federation(tok["federation_id"])
    rr = tok["artifact_hex"]
    nn = tok["namespace_id"]
    ii = tok["iteration"]
    ll = tok["language"]
    aa = tok["actor_aa"]
    if n_tokens <= 3:
        return "LUP:%s-%s-%s" % (ff, rr, nn)
    if n_tokens == 4:
        return "LUP:%s-%s-%s-%s" % (ff, rr, nn, ii)
    if n_tokens == 5:
        return "LUP:%s-%s-%s-%s-%s" % (ff, rr, nn, ii, ll)
    return "LUP:%s-%s-%s-%s-%s-%s" % (ff, rr, nn, ii, ll, aa)


def expand_lup_id(lup_id):
    """Return (kind, tokens_dict_or_none, message, used_x).

    kind: canonical | short | medium | full | legacy_6field | legacy_rgb | invalid
    Canonical machine 4.2.4: LUP:FFFFFF-RRRRRR-NN-II-LL-AA (FF always 6-hex).
    Human input may use X for federation 000001.
    """
    if not lup_id.startswith("LUP:"):
        return ("invalid", None, "missing LUP: prefix", False)
    parts = lup_id[4:].split("-")
    n = len(parts)

    def pack(ff, rr, nn, ii, ll, aa, used_x):
        return (
            {
                "federation_id": ff,
                "artifact_hex": rr,
                "namespace_id": nn,
                "iteration": ii,
                "language": ll,
                "actor_aa": aa,
            },
            used_x,
        )

    ff_raw = parts[0] if n >= 1 else ""
    ff, used_x, ff_err = normalize_federation_token(ff_raw)

    colon_bad = None
    for idx, part in enumerate(parts):
        if LINEAGE_DELIM in part and idx != 1:
            colon_bad = "HDR_LUP_COLON_ELSEWHERE: colon only allowed in RRRRRR (and LUP: prefix)"
            break

    rr_machine = None
    if n >= 2:
        _origin, _num, rr_machine, rr_err = parse_artifact_token(parts[1])
    else:
        rr_err = "missing RRRRRR"

    if colon_bad:
        return ("invalid", None, colon_bad, False)

    if n == 3 and ff and rr_machine and HEX2.match(parts[2]):
        tok, used_x = pack(ff, rr_machine, parts[2], "00", "EN", "00", used_x)
        return ("short", tok, "", used_x)
    if n == 4 and ff and rr_machine and HEX2.match(parts[2]) and HEX2.match(parts[3]):
        tok, used_x = pack(ff, rr_machine, parts[2], parts[3], "EN", "00", used_x)
        return ("medium", tok, "", used_x)
    if (
        n == 5
        and ff
        and rr_machine
        and HEX2.match(parts[2])
        and HEX2.match(parts[3])
        and LETTERS2.match(parts[4])
    ):
        tok, used_x = pack(ff, rr_machine, parts[2], parts[3], parts[4], "00", used_x)
        return ("full", tok, "", used_x)
    if (
        n == 6
        and ff
        and rr_machine
        and HEX2.match(parts[2])
        and HEX2.match(parts[3])
        and LETTERS2.match(parts[4])
        and HEX2.match(parts[5])
    ):
        tok, used_x = pack(ff, rr_machine, parts[2], parts[3], parts[4], parts[5], used_x)
        return ("canonical", tok, "", used_x)
    if n in (3, 4, 5, 6) and rr_err:
        return ("invalid", None, rr_err, False)
    if (
        n == 7
        and HEX6.match(parts[0])
        and HEX6.match(parts[1])
        and HEX2.match(parts[2])
        and HEX2.match(parts[3])
        and LETTERS2.match(parts[4])
        and HEX2.match(parts[5])
        and HEX6.match(parts[6])
    ):
        return ("legacy_rgb", None, "LUP:FFFFFF-RGB-NN-II-LL-AA-RRRRRR", False)
    if n == 5 and HEX2.match(parts[1]) and LETTERS2.match(parts[2]):
        return ("legacy_6field", None, "LUP:FFFFFF-GG-LL-II-RRRRRR", False)
    if n == 6 and HEX2.match(parts[1]) and HEX2.match(parts[2]) and LETTERS2.match(parts[3]):
        return ("legacy_6field", None, "LUP:FFFFFF-NN-AA-LL-II-RRRRRR", False)
    if ff_err and n in (3, 4, 5, 6):
        return ("invalid", None, ff_err, False)
    return ("invalid", None, "unparseable %s" % lup_id, False)


def canonical_string(tok):
    """Machine export. Never emits X. Always six-hex federation."""
    ff, _used_x, err = normalize_federation_token(tok["federation_id"])
    if err or ff is None:
        ff = tok["federation_id"]
    return "LUP:%s-%s-%s-%s-%s-%s" % (
        ff,
        tok["artifact_hex"],
        tok["namespace_id"],
        tok["iteration"],
        tok["language"],
        tok["actor_aa"],
    )


def parse_version_tuple(text):
    parts = []
    for chunk in (text or "").split("."):
        if chunk.isdigit():
            parts.append(int(chunk))
        else:
            return None
    while len(parts) < 3:
        parts.append(0)
    return tuple(parts[:3])


def first_named_block(text, marker):
    start = text.find(marker)
    if start < 0:
        return ""
    rest = text[start + len(marker) :]
    lines = []
    for line in rest.splitlines():
        if line.startswith("---"):
            break
        if line and not line[0].isspace() and ":" in line:
            break
        lines.append(line)
    return "\n".join(lines)


def first_identity_block(text):
    return first_named_block(text, "lupopedia.identity:")


def first_map_block(text):
    return first_named_block(text, "lupopedia.map:")


def extract_block_keys(block):
    keys = {}
    for line in block.splitlines():
        m = KEY_RE.match(line)
        if m:
            keys[m.group(1)] = m.group(2).upper()
    return keys


def extract_identity(text):
    block = first_identity_block(text)
    keys = extract_block_keys(block)
    header_slice = text[:4000]
    hfv_m = HFV_RE.search(header_slice)
    id_m = ID_RE.search(block) or ID_RE.search(header_slice)
    map_keys = extract_block_keys(first_map_block(text))
    return {
        "header_format_version": hfv_m.group(1) if hfv_m else "",
        "lupopedia_id": id_m.group(1).upper() if id_m else "",
        "keys": keys,
        "map_keys": map_keys,
        "identity_raw": block,
    }


def value_has_forbidden_delim(value):
    if value is None:
        return False
    if FORBIDDEN_DELIM_RE.search(value):
        return True
    for ch in value:
        if ord(ch) > 126:
            return True
    return False


def parse_lup_hex(value):
    """Return (ok, message). HEX identity uses dots, MODE=HEX, no hyphen."""
    if not value:
        return (False, "missing LUP.HEX")
    if value_has_forbidden_delim(value):
        return (False, "HDR_LUP_DELIM: HEX must use dots only")
    parts = value.split(".")
    if len(parts) < 6:
        return (False, "HDR_LUP_HEX: expected PROTOCOL.MODE.NODE...VERSION")
    if parts[0] != "PRT" or parts[1] != "HEX":
        return (False, "HDR_LUP_HEX: MODE must be HEX")
    return (True, "")


def validate_key_identity(keys, map_keys, migration_mode):
    errors = []
    warns = []
    for key, val in keys.items():
        if key.startswith("LUP.") or key == "LUPOPEDIA":
            if value_has_forbidden_delim(val) and key != "LUP.OMIT":
                errors.append("HDR_LUP_DELIM: %s uses hyphen/pipe/non-ASCII" % key)
    key_val = keys.get("LUP.KEY", "")
    if key_val and key_val != LUP_KEY_CANONICAL:
        errors.append("HDR_LUP_KEY_ORDER: %s != %s" % (key_val, LUP_KEY_CANONICAL))
    elif not key_val:
        errors.append("HDR_LUP_KEY_ORDER: LUP.KEY missing")
    hex_val = keys.get("LUP.HEX", "")
    ok, msg = parse_lup_hex(hex_val)
    if not ok:
        errors.append(msg if msg.startswith("HDR_") else "HDR_LUP_HEX: %s" % msg)
    if not map_keys:
        warns.append("HDR_LUP_MAP_REQUIRED: lupopedia.map missing")
    else:
        idx = map_keys.get("index", "")
        ok, msg = parse_lup_hex(idx)
        if not ok:
            errors.append("HDR_LUP_HEX: map.index %s" % (msg or "invalid"))
    return errors, warns


def validate_record(path, rec, migration_mode):
    errors = []
    warns = []
    hfv = rec["header_format_version"]
    vt = parse_version_tuple(hfv)
    lup_id = rec["lupopedia_id"]
    keys = rec["keys"]
    map_keys = rec.get("map_keys") or {}
    has_key_grammar = "LUP.KEY" in keys or "LUP.HEX" in keys

    if vt is not None and vt < (4, 2, 1):
        msg = "HDR_LUP_PRE_421: header_format_version %s is older than 4.2.1" % hfv
        if lup_id:
            errors.append(msg)
            return errors, warns
        warns.append(msg)
        return errors, warns

    if has_key_grammar:
        k_err, k_warn = validate_key_identity(keys, map_keys, migration_mode)
        errors.extend(k_err)
        warns.extend(k_warn)
        if lup_id and vt is not None and vt >= (4, 2, 11) and not migration_mode:
            errors.append("HDR_LUP_DELIM: hyphen lupopedia_id is not part of 4.2.11 KEY grammar")
        if not lup_id:
            return errors, warns

    if not lup_id:
        msg = "HDR_LUP_ID_REQUIRED: lupopedia.identity.lupopedia_id missing"
        if vt is not None and vt >= (4, 2, 3) and not migration_mode:
            errors.append(msg)
        else:
            warns.append(msg)
        return errors, warns

    kind, tok, detail, used_x = expand_lup_id(lup_id)
    if kind == "legacy_6field":
        msg = "HDR_LUP_LEGACY_6FIELD: %s (expected LUP:FFFFFF-RRRRRR-NN-II-LL-AA)" % lup_id
        if migration_mode or (vt is not None and vt < (4, 2, 3)):
            warns.append(msg)
        else:
            errors.append(msg)
        return errors, warns
    if kind == "legacy_rgb":
        msg = "HDR_LUP_LEGACY_RGB: %s (color is not identity; expected FFFFFF-RRRRRR-NN-II-LL-AA)" % lup_id
        if migration_mode or (vt is not None and vt < (4, 2, 3)):
            warns.append(msg)
        else:
            errors.append(msg)
        return errors, warns
    if kind == "invalid" or tok is None:
        errors.append("HDR_LUP_ID_MISMATCH: %s" % detail)
        return errors, warns
    if kind in ("short", "medium", "full"):
        warns.append("HDR_LUP_SHORTFORM: expanded %s -> %s" % (kind, canonical_string(tok)))
    if used_x:
        warns.append(
            "HDR_LUP_FED_COMPRESS: X expanded to %s (machine export %s)"
            % (ROOT_FEDERATION_ID, canonical_string(tok))
        )
        if kind == "canonical" and vt is not None and vt >= (4, 2, 4) and not migration_mode:
            errors.append(
                "HDR_LUP_FED_X_ON_DISK: stored lupopedia_id must use machine FF %s not X"
                % ROOT_FEDERATION_ID
            )

    ff = tok["federation_id"]
    rr = tok["artifact_hex"]
    nn = tok["namespace_id"]
    ii = tok["iteration"]
    ll = tok["language"]
    aa = tok["actor_aa"]

    if ff in ("000000", "FFFFFF"):
        errors.append("HDR_LUP_FF_RESERVED: federation_id %s is reserved" % ff)
    if not HEX6.match(ff):
        errors.append("HDR_LUP_FF_WIDTH: federation_id must be 6 uppercase hex")
    origin_ff, artifact_num, rr_machine, rr_err = parse_artifact_token(rr)
    if rr_err:
        errors.append(rr_err)
    elif rr_machine != rr:
        tok["artifact_hex"] = rr_machine
        rr = rr_machine
    if origin_ff is not None and origin_ff == ff:
        errors.append(
            "HDR_LUP_RR_ORIGIN: origin federation %s must differ from current FF" % origin_ff
        )
    if "origin_federation_id" in keys:
        key_origin, _ox, oerr = normalize_federation_token(keys["origin_federation_id"])
        if oerr:
            errors.append("HDR_LUP_RR_ORIGIN: origin_federation_id %s invalid" % keys["origin_federation_id"])
        elif origin_ff is None:
            errors.append("HDR_LUP_RR_ORIGIN: origin_federation_id set but RRRRRR has no colon")
        elif key_origin != origin_ff:
            errors.append(
                "HDR_LUP_ID_MISMATCH: origin_federation_id %s != token %s"
                % (keys["origin_federation_id"], origin_ff)
            )
    if nn == "00" or not HEX2.match(nn):
        errors.append("HDR_LUP_NN_RANGE: namespace_id %s not in 01..FF" % nn)
    if not HEX2.match(aa):
        errors.append("HDR_LUP_AA_RANGE: actor_aa %s not in 00..FF" % aa)
    if not is_allowed_ll(ll):
        errors.append("HDR_LUP_LL_ISO: language %s is not ISO 639-1 or reserved ZZ" % ll)
    if not HEX2.match(ii):
        errors.append("HDR_LUP_II_HEX: iteration %s is not 2 hex" % ii)

    expected = canonical_string(tok)
    if kind == "canonical" and not used_x and lup_id != expected:
        errors.append("HDR_LUP_ID_MISMATCH: %s != %s" % (lup_id, expected))

    for key, val in list(keys.items()):
        if key == "federation_id":
            key_ff, key_used_x, key_err = normalize_federation_token(val)
            if key_err:
                errors.append("HDR_LUP_FF_WIDTH: federation_id %s invalid" % val)
                continue
            if key_used_x:
                warns.append(
                    "HDR_LUP_FED_COMPRESS: identity.federation_id X -> %s" % ROOT_FEDERATION_ID
                )
                if vt is not None and vt >= (4, 2, 4) and not migration_mode:
                    errors.append(
                        "HDR_LUP_FED_X_ON_DISK: federation_id key must store %s not X"
                        % ROOT_FEDERATION_ID
                    )
            if key_ff != ff:
                errors.append(
                    "HDR_LUP_ID_MISMATCH: federation_id %s != token %s" % (val, ff)
                )
            continue
        if key in tok and keys[key] != tok[key]:
            errors.append("HDR_LUP_ID_MISMATCH: %s %s != token %s" % (key, keys[key], tok[key]))
    if "group_id" in keys and "namespace_id" not in keys:
        warns.append("HDR_LUP_LEGACY_6FIELD: group_id present; rename to namespace_id")
    if "color_hex" in keys:
        errors.append("HDR_LUP_COLOR_IN_ID: color_hex must not appear under lupopedia.identity")
    if "actor_hex" in text_lower_identity_keys(keys):
        errors.append("HDR_LUP_ACTOR_HEX_IN_ID: actor_hex must not appear as a LUP token")

    return errors, warns


def text_lower_identity_keys(keys):
    return [k.lower() for k in keys.keys()]


def main(argv):
    parser = argparse.ArgumentParser(description="Validate LUP 4.2.4 identity blocks")
    parser.add_argument("paths", nargs="+")
    parser.add_argument(
        "--migration",
        action="store_true",
        help="Accept 4.2.2 six-field IDs as WARN instead of ERROR; allow X on disk",
    )
    args = parser.parse_args(argv)
    fail = 0
    for path in args.paths:
        if not os.path.isfile(path):
            print("[ERROR] %s: not a file" % path)
            fail += 1
            continue
        with open(path, "r") as handle:
            text = handle.read()
        rec = extract_identity(text)
        errors, warns = validate_record(path, rec, args.migration)
        for item in warns:
            print("[WARN] %s: %s" % (path, item))
        for item in errors:
            print("[ERROR] %s: %s" % (path, item))
            fail += 1
        if not errors and not warns:
            print("[OK] %s" % path)
    return 1 if fail else 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
