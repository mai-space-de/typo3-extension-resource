# typo3-extension-resource

🟠🧩 TYPO3 Extension for managing Downloads, Vorlagen (Templates), Formulare (Forms) & Info-Boxen with FE group access control.

## Features

- **Model: Resource** — Title, Description, FAL file attachment, Category, Access Group
- **Model: ResourceCategory** — Types: Download, Vorlage, Formular, Info
- **Access control** via FE group: public / members only (`fe_group` + `access_group` field)
- **4 Frontend Plugins:**
  - Download List — lists resources of type "Download"
  - Categorized Overview — groups all resources by their category
  - Info-Box Widget — displays resources of type "Info"
  - Inline Form Preview — shows resources of type "Formular" with inline PDF preview

## Extension Key

`resource`

## Composer

```bash
composer require maispace/resource
```

## Installation

1. Install the extension via Composer or the Extension Manager.
2. Run database compare to create the required tables.
3. Include the TypoScript setup from `EXT:mai_resource/Configuration/TypoScript/setup.typoscript` or use the provided Site Set `maispace/resource`.
4. Add the plugins to your page using the TYPO3 backend.

## Database Tables

- `tx_mairesource_domain_model_resource` — Resources
- `tx_mairesource_domain_model_resourcecategory` — Resource Categories

## Access Control

Each resource has an `access_group` field:
- `0` = **Public** — visible to everyone
- `1` = **Members only** — visible only to logged-in frontend users

Additionally, the standard TYPO3 `fe_group` field is available for fine-grained access restriction by frontend user group.

## Templates

Override the default templates by setting the template root paths in TypoScript:

```typoscript
plugin.tx_resource {
    view {
        templateRootPaths {
            10 = EXT:your_extension/Resources/Private/Templates/
        }
    }
}
```