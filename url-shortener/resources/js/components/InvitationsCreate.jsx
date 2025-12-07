import React from 'react';

const humanize = (value) =>
    value
        .split('_')
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join(' ');

export default function InvitationsCreate({
    action,
    csrfToken,
    roles = [],
    companies = [],
    isSuperAdmin = false,
    companyName = null,
    email = '',
    selectedRole = '',
    selectedCompanyId = '',
    indexUrl,
}) {
    return (
        <section className="mx-auto max-w-3xl">
            <div className="space-y-6">
                <header>
                    <h1 className="text-3xl font-semibold tracking-tight text-slate-900">Send Invitation</h1>
                    <p className="mt-2 text-sm text-slate-500">
                        Give teammates instant access. Choose a role to control what they can manage inside the platform.
                    </p>
                </header>

                <form method="POST" action={action} className="card space-y-6">
                    <input type="hidden" name="_token" value={csrfToken} />
                    <div className="grid gap-6 md:grid-cols-2">
                        <div className="md:col-span-2">
                            <label htmlFor="email" className="form-label">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                defaultValue={email}
                                required
                                className="form-control"
                                placeholder="colleague@company.com"
                            />
                        </div>
                        <div>
                            <label htmlFor="role" className="form-label">Role</label>
                            <select id="role" name="role" defaultValue={selectedRole} required className="form-control">
                                <option value="">Select a role</option>
                                {roles.map((role) => (
                                    <option key={role} value={role}>
                                        {humanize(role)}
                                    </option>
                                ))}
                            </select>
                        </div>
                        {isSuperAdmin ? (
                            <div>
                                <label htmlFor="company_id" className="form-label">Company</label>
                                <select
                                    id="company_id"
                                    name="company_id"
                                    defaultValue={selectedCompanyId ?? ''}
                                    className="form-control"
                                >
                                    <option value="">Select a company</option>
                                    {companies.map((company) => (
                                        <option key={company.id} value={String(company.id)}>
                                            {company.name}
                                        </option>
                                    ))}
                                </select>
                                <p className="form-helper mt-2">Leave this blank only when inviting another super admin.</p>
                            </div>
                        ) : (
                            <div className="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                                Admins can invite new Admins or Members to {companyName ?? 'their company'}.
                            </div>
                        )}
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        {indexUrl && (
                            <a href={indexUrl} className="btn-secondary">Cancel</a>
                        )}
                        <button type="submit" className="btn-primary">Send Invitation</button>
                    </div>
                </form>
            </div>
        </section>
    );
}
