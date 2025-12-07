import React from 'react';

export default function UrlsCreate({ action, csrfToken, destination = '', indexUrl }) {
    return (
        <section className="mx-auto max-w-2xl">
            <div className="space-y-6">
                <header>
                    <h1 className="text-3xl font-semibold tracking-tight text-slate-900">Create Short URL</h1>
                    <p className="mt-2 text-sm text-slate-500">
                        Turn long destinations into memorable, trackable short links for your team.
                    </p>
                </header>

                <form method="POST" action={action} className="card space-y-6">
                    <input type="hidden" name="_token" value={csrfToken} />
                    <div>
                        <label htmlFor="destination" className="form-label">Destination URL</label>
                        <input
                            id="destination"
                            type="url"
                            name="destination"
                            defaultValue={destination}
                            required
                            className="form-control"
                            placeholder="https://example.com/landing"
                        />
                        <p className="form-helper mt-2">
                            Paste the full URL you want to shorten. We&#39;ll generate a unique slug automatically.
                        </p>
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        {indexUrl && (
                            <a href={indexUrl} className="btn-secondary">Cancel</a>
                        )}
                        <button type="submit" className="btn-primary">Generate Link</button>
                    </div>
                </form>
            </div>
        </section>
    );
}
