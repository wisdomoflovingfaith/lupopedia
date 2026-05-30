# Ensure every actor has apps/ with skills/skills.md and ROOT doctrine structure (4.0.67)
$base = (Resolve-Path (Join-Path $PSScriptRoot ".." "actors")).Path
$actors = Get-ChildItem -Path $base -Directory -Name | Where-Object { $_ -ne "_template_apps" }

$skillsMd = @"
# Skill Registry — Actor Application Folder (4.0.67)

Canonical skill registry for this actor. Single source of truth for capabilities (ROOT doctrine).

## SKILL INDEX

- (Add skill keys this actor claims. Example: `doctrine_check`, `flare_headers`, `schema_audit`.)

## SKILL DEFINITIONS

For each skill include:

- **Name**
- **Purpose**
- **Input/Output contract**
- **Dependencies** (other skills, assets, scripts)
- **Version**

## FAUCET COMPATIBILITY

Which LLMs, prompts, or runtime modes this skill supports.

## CHANGELOG

Required for lineage tracking. Date and change summary.
"@

$schemaMd = @"
# Actor App Schema

Describes how this actor uses its skills and assets. Machine-readable index is in `manifest.json`.

- **skills/** — skills.md and optional *.skill.md files
- **scripts/** — shell, Python, or Lupopedia-native scripts
- **assets/** — icons, images, prompts, templates (content-addressed)
"@

$manifestJson = '{"actor_id":null,"version":"1.0","skills":[],"assets":[],"scripts":[],"updated_at":0,"schema_version":"1.0"}'

foreach ($a in $actors) {
    $apps = Join-Path $base $a "apps"
    $null = New-Item -ItemType Directory -Force -Path (Join-Path $apps "skills")
    $null = New-Item -ItemType Directory -Force -Path (Join-Path $apps "scripts")
    $null = New-Item -ItemType Directory -Force -Path (Join-Path $apps "assets\icons")
    $null = New-Item -ItemType Directory -Force -Path (Join-Path $apps "assets\images")
    $null = New-Item -ItemType Directory -Force -Path (Join-Path $apps "assets\prompts")
    $null = New-Item -ItemType Directory -Force -Path (Join-Path $apps "assets\templates")
    $null = New-Item -ItemType Directory -Force -Path (Join-Path $apps "references")
    Set-Content -Path (Join-Path $apps "skills\skills.md") -Value $skillsMd -Encoding UTF8
    Set-Content -Path (Join-Path $apps "references\schema.md") -Value $schemaMd -Encoding UTF8
    Set-Content -Path (Join-Path $apps "references\manifest.json") -Value $manifestJson -Encoding UTF8
    foreach ($sub in @("scripts", "assets\icons", "assets\images", "assets\prompts", "assets\templates")) {
        $gitkeep = Join-Path $apps $sub ".gitkeep"
        if (-not (Test-Path $gitkeep)) { Set-Content -Path $gitkeep -Value "" -Encoding UTF8 }
    }
}
Write-Host "Actor apps structure ensured for: $($actors -join ', ')"
