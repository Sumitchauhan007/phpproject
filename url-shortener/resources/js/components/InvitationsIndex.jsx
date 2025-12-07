import React from 'react';

const humanize = (value) =>
    value
        .split('_')
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join(' ');

export default function InvitationsIndex({ invitations = [], canInvite = false, createUrl }) {
    const hasInvitations = invitations.length > 0;

    return (
        <section className="space-y-6">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 className="text-3xl font-semibold tracking-tight text-slate-900">Invitations</h1>
                    <p className="mt-2 text-sm text-slate-500">
                        Monitor outstanding invites, resend links, and keep your organisation secure.
                    </p>
                </div>
                {canInvite && (
                    <a href={createUrl} className="btn-primary self-start sm:self-auto">Invite Someone</a>
                )}
            </div>

            <div className="table-wrapper">
                <table className="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Company</th>
                            <th scope="col">Invited By</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {!hasInvitations ? (
                            <tr>
                                <td colSpan="5" className="px-4 py-8 text-center text-sm text-slate-500">
                                    No invitations found. Send your first invite to collaborate with teammates.
                                </td>
                            </tr>
                        ) : (
                            invitations.map((invitation, index) => (
                                <tr key={invitation.id} className={index % 2 === 0 ? undefined : 'bg-slate-50/60'}>
                                    <td className="font-medium text-slate-900">{invitation.email}</td>
                                    <td>{humanize(invitation.role)}</td>
                                    <td>{invitation.company ?? '—'}</td>
                                    <td>{invitation.invited_by ?? '—'}</td>
                                    <td>
                                        {invitation.accepted_at ? (
                                            <span className="badge badge-success">Accepted</span>
                                        ) : (
                                            <span className="badge badge-pending">Pending</span>
                                        )}
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        </section>
    );
}
