---
wolfie.headers.version: 4.0.1
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created CONTRIBUTOR_TRAINING.md: Minimum skill requirements and coding standards for Lupopedia contributors. OOP requirements, class structure rules, function definition requirements."
tags:
  categories: ["documentation", "training", "contributors"]
  collections: ["core-docs", "dev"]
  channels: ["dev"]
header_atoms:
  - GLOBAL_CURRENT_AUTHORS
  - GLOBAL_CURRENT_VERSION
in_this_file_we_have:
  - Minimum skill requirements
  - Object-oriented programming requirements
  - Class structure and function definition rules
  - Coding standards
  - Doctrine alignment requirements
file:
  title: "Contributor Training - Minimum Requirements"
  description: "Minimum skill requirements and coding standards for Lupopedia contributors. OOP knowledge, class structure rules, and doctrine alignment."
  version: GLOBAL_CURRENT_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸŸ¦ **Contributor Training â€” Minimum Requirements**

This document defines the **minimum skill requirements** and **coding standards** for Lupopedia contributors.

Contributors must understand and correctly apply these concepts. This is **not optional**. It is **foundational**.

---

## **Object-Oriented Programming Requirements**

Contributors must understand and correctly apply object-oriented programming.  
Lupopedia is built on **disciplined class design**, not ad-hoc procedural scripts.

### **Required OOP Knowledge**

Contributors must demonstrate clear understanding of:

- **classes and objects**  
- **constructors and destructors**  
- **public, private, and protected visibility**  
- **inheritance and composition**  
- **interfaces and abstract classes**  
- **method signatures**  
- **static vs instance methods**  
- **encapsulation and data hiding**  
- **dependency boundaries**  
- **predictable class lifecycles**  

If a contributor cannot explain these concepts clearly, they are **not ready** to write code for Lupopedia.

---

## **Class Structure and Function Definition Rules**

Every class file **MUST** begin with:

1. **A WOLFIE Header**  
2. **A complete list of all method signatures**  
3. **A short description of each method's purpose**  
4. **Security notes** (if applicable)

This mirrors the Crafty Syntax discipline and ensures that both humans and AI agents can understand a class in seconds.

### **Required Structure**

```php
---
wolfie.headers.version: 4.0.1
header_atoms:
  - GLOBAL_CURRENT_VERSION
file:
  author: GLOBAL_CURRENT_AUTHORS
  version: GLOBAL_CURRENT_VERSION
---

class ExampleClass {

    // --------------------------------------------------------------
    // FUNCTION DEFINITIONS (REQUIRED)
    // --------------------------------------------------------------
    // function doSomething($input)
    // function validateUser($id)
    // function renderOutput()
    // --------------------------------------------------------------

    public function doSomething($input) {
        // implementation
    }

    private function validateUser($id) {
        // implementation
    }

    public function renderOutput() {
        // implementation
    }
}
```

### **Rules**

- All method signatures **MUST** be listed at the top of the class
- The list must be **complete** and kept in sync with the class body
- **No hidden methods.** **No magic.** **No undocumented behavior.**
- The header + signature block is **mandatory** for every class
- Method signatures must include visibility (`public`, `private`, `protected`)
- Method signatures must include parameter names (even if not types)
- Static methods must be marked with `static` in the signature list

### **Why This Matters**

Lupopedia is a **semantic OS**. Agents read class files. Documentation references class metadata. The system depends on **predictable structure**.

Contributors must write code that is:

- **readable**  
- **analyzable**  
- **predictable**  
- **deterministic**  
- **doctrine-aligned**  

This is not optional. It is foundational.

---

## **Additional Requirements**

### **Doctrine Alignment**

All code must follow Lupopedia doctrine:

- **No foreign keys** (see [NO_FOREIGN_KEYS_DOCTRINE.md](../../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md))
- **BIGINT UTC timestamps** (see [WOLFIE_TIMESTAMP_DOCTRINE.md](WOLFIE_TIMESTAMP_DOCTRINE.md))
- **PDO, not mysqli** (see [PDO_CONVERSION_DOCTRINE.md](../../doctrine/PDO_CONVERSION_DOCTRINE.md))
- **No framework dependencies** (see [WHY_NO_FRAMEWORKS.md](../../doctrine/WHY_NO_FRAMEWORKS.md))
- **WOLFIE Headers** on all files (see [WOLFIE_HEADER_SPECIFICATION.md](../../agents/WOLFIE_HEADER_SPECIFICATION.md))

### **Documentation Standards**

All code must use:

- **Atom references** instead of hardcoded values (see [DOCUMENTATION_DOCTRINE.md](../../doctrine/DOCUMENTATION_DOCTRINE.md))
- **WOLFIE Headers** with proper metadata
- **Inline comments** for complex logic
- **Method signature blocks** at class top (as defined above)

---

## **Testing Requirements**

Before submitting code, contributors must:

- Verify class structure follows required format
- Ensure all method signatures are listed
- Check that WOLFIE Header is present and correct
- Validate atom references (if used)
- Confirm doctrine compliance

---

## **Related Documentation**

- **[DOCUMENTATION_DOCTRINE.md](../../doctrine/DOCUMENTATION_DOCTRINE.md)** â€” Documentation is software, data, for machines
- **[CURSOR_REFACTOR_DOCTRINE.md](../../doctrine/CURSOR_REFACTOR_DOCTRINE.md)** â€” MANDATORY rules for rewriting legacy code
- **[PDO_CONVERSION_DOCTRINE.md](../../doctrine/PDO_CONVERSION_DOCTRINE.md)** â€” PDO conversion requirements
- **[WOLFIE_HEADER_SPECIFICATION.md](../../agents/WOLFIE_HEADER_SPECIFICATION.md)** â€” WOLFIE Header format
- **[NO_FOREIGN_KEYS_DOCTRINE.md](../../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md)** â€” Database doctrine

---

**These requirements are non-negotiable.  
Contributors must meet these standards before writing code for Lupopedia.**
