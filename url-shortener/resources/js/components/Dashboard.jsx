import React from 'react';

const humanize = (value) =>
    value
        .split('_')
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join(' ');

const EmptyState = ({ colSpan, message }) => (
    <tr>
        <td colSpan={colSpan} className="px-4 py-8 text-center text-sm text-slate-500">
            {message}
        </td>
    </tr>
);

const UrlTable = ({ urls, emptyMessage }) => (
    <div className="table-wrapper">
        <table className="data-table">
            <thead>
                <tr>
                    <th scope="col">Slug</th>
                    <th scope="col">Destination</th>
                    <th scope="col">Company</th>
                    <th scope="col">Creator</th>
                </tr>
            </thead>
            <tbody>
                {urls.length === 0 ? (
                    <EmptyState colSpan={4} message={emptyMessage} />
                ) : (
                    urls.map((url, index) => (
                        <tr key={url.id} className={index % 2 === 0 ? undefined : 'bg-slate-50/60'}>
                            <td className="font-medium text-slate-900">{url.slug}</td>
                            <td className="break-words text-slate-600">{url.destination}</td>
                            <td>{url.company ?? '—'}</td>
                            <td>{url.creator ?? '—'}</td>
                        </tr>
                    ))
                )}
            </tbody>
        </table>
    </div>
);

export default function Dashboard({ user, companies = [], urls = [] }) {
    const title = humanize(user.role);

    return (
        <section className="space-y-8">
            <div className="rounded-3xl bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 p-8 text-white shadow-xl">
                <div className="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p className="text-sm font-medium uppercase tracking-[0.2em] text-white/70">Welcome back</p>
                        <h1 className="mt-3 text-3xl font-semibold tracking-tight md:text-4xl">{user.name}</h1>
                        <p className="mt-2 text-sm text-white/80">You&#39;re signed in as {title}. Here&#39;s what&#39;s new.</p>
                    </div>
                    <div className="card-tight bg-white/10 text-white/90 shadow-none">
                        <dl className="grid grid-cols-2 gap-4">
                            <div>
                                <dt className="text-xs uppercase tracking-wide text-white/70">Companies</dt>
                                <dd className="mt-1 text-2xl font-semibold">{companies.length}</dd>
                            </div>
                            <div>
                                <dt className="text-xs uppercase tracking-wide text-white/70">Short URLs</dt>
                                <dd className="mt-1 text-2xl font-semibold">{urls.length}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {user.role === 'super_admin' ? (
                <div className="space-y-4">
                    <div className="flex items-center justify-between">
                        <h2 className="text-xl font-semibold text-slate-900">Companies Overview</h2>
                        <p className="text-sm text-slate-500">Track each organisation and active members.</p>
                    </div>
                    <div className="grid gap-4 md:grid-cols-2">
                        {companies.length === 0 ? (
                            <div className="card text-sm text-slate-500">No companies yet. Start by inviting a new admin.</div>
                        ) : (
                            companies.map((company) => (
                                <div key={company.id} className="card">
                                    <div className="flex items-center justify-between">
                                        <h3 className="text-lg font-semibold text-slate-900">{company.name}</h3>
                                        <span className="badge badge-pending">{company.users_count} users</span>
                                    </div>
                                    <p className="mt-3 text-sm text-slate-500">
                                        Invite admins or members to help manage short links for this company.
                                    </p>
                                </div>
                            ))
                        )}
                    </div>
                </div>
            ) : (
                <div className="space-y-4">
                    <div className="flex items-center justify-between">
                        <h2 className="text-xl font-semibold text-slate-900">Recent Short URLs</h2>
                        <p className="text-sm text-slate-500">Stay on top of the latest links your team created.</p>
                    </div>
                    <UrlTable urls={urls} emptyMessage="No URLs available yet. Create your first short link to see it here." />
                </div>
            )}
        </section>
    );
}
