import React from 'react';

export default function LoginForm({ action, csrfToken, email = '', remember = false }) {
    return (
        <section className="mx-auto flex min-h-[70vh] w-full max-w-5xl items-center justify-center px-4">
            <div className="grid w-full gap-10 rounded-3xl bg-white/80 px-6 py-10 shadow-xl backdrop-blur md:grid-cols-[1.2fr_1fr] md:px-10">
                <div className="space-y-6">
                    <p className="text-sm font-medium uppercase tracking-[0.25em] text-indigo-500">Welcome back</p>
                    <h1 className="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">Sign in to your workspace</h1>
                    <p className="text-sm leading-relaxed text-slate-500">
                        Access analytics, manage short links, and collaborate with your team. 
                    </p>
                    <div className="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-4 text-sm text-indigo-700">
                        Tip: The demo accounts use the password <span className="font-semibold">password</span>.
                    </div>
                </div>

                <form method="POST" action={action} className="card space-y-6">
                    <input type="hidden" name="_token" value={csrfToken} />
                    <div>
                        <label htmlFor="email" className="form-label">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            defaultValue={email}
                            autoFocus
                            required
                            className="form-control"
                            placeholder="you@example.com"
                        />
                    </div>
                    <div>
                        <label htmlFor="password" className="form-label">Password</label>
                        <input id="password" type="password" name="password" required className="form-control" placeholder="••••••••" />
                    </div>
                    <label htmlFor="remember" className="flex items-center gap-3 text-sm text-slate-600">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            defaultChecked={remember}
                            className="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        />
                        Keep me signed in
                    </label>
                    <button type="submit" className="btn-primary w-full">Login</button>
                </form>
            </div>
        </section>
    );
}
