# Database Schema CSV Reference

This directory contains CSV representations of database tables for use with AI coding assistants. These files serve as a reliable reference for the database schema when direct database access is not available or practical.

## File Format

Each CSV file follows this structure:
- **Row 0**: Column names
- **Row 1**: Column data types
- **Subsequent rows**: Example data (if available)

## Purpose

These CSV files are used to:
- Provide consistent schema information to AI assistants
- Prevent hallucinations about database structure
- Enable parallel development across multiple AI models
- Serve as a lightweight, version-controlled schema reference

## Usage

When working with AI assistants:
1. Include the relevant CSV file in your prompt
2. Reference the column names and types when writing queries
3. Use the example data to understand the expected format

## Security Note

- These files may contain non-sensitive example data
- Never include real credentials or sensitive information
- The actual database may contain additional constraints and indexes not shown here

## File Naming

Files are named after their corresponding database tables, with the prefix removed for clarity. For example:
- `auth_providers.csv` represents `{DB_PREFIXTABLE_PREFIX}auth_providers`

## Adding New Tables

When adding a new table to the database:
1. Create a new CSV file in this directory
2. Follow the standard format (headers, types, examples)
3. Update this README if needed
