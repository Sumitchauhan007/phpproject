import React from 'react';

export default function UrlsIndex({ urls = [], canCreate = false, createUrl }) {
    const hasUrls = urls.length > 0;
    const appOrigin = typeof window !== 'undefined' ? window.location.origin : '';

    const resolveShortUrl = (url) => {
        if (url.shortUrl) {
            return url.shortUrl;
        }

        if (appOrigin) {
            return `${appOrigin}/s/${url.slug}`;
        }

        return `/s/${url.slug}`;
    };

    return (
        <section className="space-y-6">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 className="text-3xl font-semibold tracking-tight text-slate-900">Short URLs</h1>
                    <p className="mt-2 text-sm text-slate-500">
                        Manage every link your team shortens and keep destinations organised.
                    </p>
                </div>
                {canCreate && (
                    <a href={createUrl} className="btn-primary self-start sm:self-auto">Create Short URL</a>
                )}
            </div>

            <div className="table-wrapper">
                <table className="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Short URL</th>
                            <th scope="col">Destination</th>
                            <th scope="col">Company</th>
                            <th scope="col">Creator</th>
                        </tr>
                    </thead>
                    <tbody>
                        {!hasUrls ? (
                            <tr>
                                <td colSpan="4" className="px-4 py-8 text-center text-sm text-slate-500">
                                    No short URLs found. Create your first link to see it listed here.
                                </td>
                            </tr>
                        ) : (
                            urls.map((url, index) => {
                                const shortUrl = resolveShortUrl(url);

                                return (
                                    <tr key={url.id} className={index % 2 === 0 ? undefined : 'bg-slate-50/60'}>
                                    <td className="font-medium text-slate-900">
                                        <a
                                            href={shortUrl}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="text-indigo-600 hover:text-indigo-700"
                                        >
                                            {shortUrl}
                                        </a>
                                    </td>
                                    <td className="break-words text-slate-600">{url.destination}</td>
                                    <td>{url.company ?? '—'}</td>
                                    <td>{url.creator ?? '—'}</td>
                                    </tr>
                                );
                            })
                        )}
                    </tbody>
                </table>
            </div>
        </section>
    );
}
