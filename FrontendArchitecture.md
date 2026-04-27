# Frontend Architecture - Attendance Manager

This document outlines the frontend architecture for the Attendance Manager application, designed to work seamlessly with the Laravel Modular Monolith backend.

## Tech Stack

- **Framework**: React (Vite/Next.js)
- **State Management & Data Fetching**: [TanStack Query](https://tanstack.com/query) (React Query)
- **Routing**: [TanStack Router](https://tanstack.com/router)
- **Tables**: [TanStack Table](https://tanstack.com/table)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **UI Components**: [shadcn/ui](https://ui.shadcn.com/) (Radix UI)

## Folder Structure (Feature-Based)

```text
src/
├── config/
│   └── api.ts
├── components/             # Shared UI components
│   └── ui/                 # shadcn/ui primitives
├── hooks/                  # Global reusable hooks
├── layouts/                # Layout wrappers (Main, Auth)
├── lib/                    # Library configs (QueryClient, utils)
|   ├── api-client.ts       # Axios instance with auth headers
│   ├── query-client.ts     # QueryClient instance
│   └── utils.ts       # Date formatting utilities
├── pages/                  # Page-level components
│   ├── auth/               # Auth pages (login, register)
│   └── hris/               # HRIS feature pages
│       ├── employees/
│       │   ├── index.tsx   # List employees
│       │   ├── create.tsx  # Create employee
│       │   ├── edit.tsx    # Edit employee
│       │   └── show.tsx    # Employee detail
│       └── departments/
│           ├── index.tsx   # List departments
│           ├── create.tsx  # Create department
│           ├── edit.tsx    # Edit department
│           └── show.tsx    # Department detail
│   ├── attendance/         # Attendance pages
│   └── schedule/           # Schedule pages
├── routes/                 # TanStack Router definitions
│   ├── __root.tsx          # Root router with outlet & devtools
│   ├── _authenticated.tsx   # Layout for authenticated users
│   ├── _guest.tsx          # Layout for guests
│   ├── _authenticated/     # Protected routes (requires login)
│   │   ├── dashboard.tsx        # /dashboard
│   │   └── hris/                # /hris/*
│   │       ├── employees/
│   │       │   └── index.tsx        # /hris/employees
│   │       │   └── create.tsx       # /hris/employees/create
│   │       │   └── edit.tsx         # /hris/employees/edit/:id
│   │       │   └── show.tsx         # /hris/employees/show/:id
│   │       └── departments/
│   │           └── index.tsx        # /hris/departments
│   │           └── create.tsx       # /hris/departments/create
│   │           └── edit.tsx         # /hris/departments/edit/:id
│   │           └── show.tsx         # /hris/departments/show/:id
│   └── _guest/            # Public routes (guests only)
│       └── login.tsx     # /login
├── services/               # Global services (auth, analytics, storage)
├── store/                  # Global state management
├── types/                  # Shared TypeScript declarations
└── utils/                  # Utility functions
```

## Implementation Guidelines

### 1. Data Fetching (TanStack Query)

All API interactions should live within the `features/{feature}/api/` folder.
Example: `src/features/hris/api/useEmployees.ts`

```typescript
export const useEmployees = (filters: EmployeeFilters) => {
    return useQuery({
        queryKey: ["employees", filters],
        queryFn: () => apiClient.get("/hris/employees", { params: filters }),
    });
};
```

### 2. Tables (TanStack Table)

Complex tables should be localized to their features.
Example: `src/features/hris/components/EmployeeTable.tsx`

- Use `@tanstack/react-table` for logic.
- Use `components/ui/table.tsx` (shadcn) for styling.

### 3. Routing (TanStack Router)

- Use file-based routing or central config in `src/routes/`.
- Protect routes using `beforeLoad` with auth state.

### 4. Components (shadcn/ui)

- Primitive UI components go to `src/components/ui/`.
- Business-logic components go to `src/features/{feature}/components/`.

## Mapping to Backend

| Frontend Feature      | Backend Module           | Primary API Endpoint   |
| :-------------------- | :----------------------- | :--------------------- |
| `features/auth`       | `Auth (Sanctum/Web)`     | `/api/auth/*`          |
| `features/hris`       | `app/Modules/HRIS`       | `/api/v1/hris/*`       |
| `features/attendance` | `app/Modules/Attendance` | `/api/v1/attendance/*` |
| `features/schedule`   | `app/Modules/Schedule`   | `/api/v1/schedule/*`   |
