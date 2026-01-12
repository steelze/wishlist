import { useCallback, useEffect, useState } from 'react';
import axios from 'axios';
import Products from '@/components/product';
import Wishlist, { type WishlistItem } from '@/components/wishlist';
import { Button } from '@/components/ui/button';
import { login, logout, register } from '@/routes';
import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { Heart } from 'lucide-react';

export default function Welcome({ canRegister = true }: { canRegister?: boolean }) {
    const { auth } = usePage<SharedData>().props;

    const [wishlistItems, setWishlistItems] = useState<WishlistItem[]>([]);
    const [wishlistLoading, setWishlistLoading] = useState(true);
    const [wishlistError, setWishlistError] = useState<string | null>(null);

    const fetchWishlist = useCallback(async () => {
        if (!auth.user) return;

        setWishlistLoading(true);
        setWishlistError(null);
        try {
            const response = await axios.get('/api/v1/wishlist');
            setWishlistItems(response.data.data);
        } catch {
            setWishlistError('Failed to load wishlist');
        } finally {
            setWishlistLoading(false);
        }
    }, [auth.user]);

    useEffect(() => {
        fetchWishlist();
    }, [fetchWishlist]);

    const handleRemoveFromWishlist = (productId: number) => {
        setWishlistItems((items) => items.filter((item) => item.id !== productId));
    };

    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen bg-background">
                <header className="border-b">
                    <div className="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
                        <div className="flex items-center gap-2">
                            <Heart className="size-6 text-primary" />
                            <span className="text-xl font-semibold">Wishlist</span>
                        </div>
                        <nav className="flex items-center gap-2">
                            {auth.user ? (
                                <Button asChild>
                                    <Link href={logout()}>Log out</Link>
                                </Button>
                            ) : (
                                <>
                                    <Button variant="ghost" asChild>
                                        <Link href={login()}>Log in</Link>
                                    </Button>
                                    {canRegister && (
                                        <Button asChild>
                                            <Link href={register()}>Register</Link>
                                        </Button>
                                    )}
                                </>
                            )}
                        </nav>
                    </div>
                </header>

                <main>
                    <section className="bg-muted/30 py-16">
                        <div className="mx-auto max-w-8xl px-4 sm:px-6">
                            <div className="flex flex-col gap-8 md:flex-row">
                                <div className={auth.user ? 'md:w-2/3' : 'w-full'}>
                                    <Products
                                        isAuthenticated={!!auth.user}
                                        onWishlistUpdate={fetchWishlist}
                                    />
                                </div>
                                {auth.user && (
                                    <div className="md:w-1/3">
                                        <div className="md:sticky md:top-4">
                                            <Wishlist
                                                items={wishlistItems}
                                                loading={wishlistLoading}
                                                error={wishlistError}
                                                onRetry={fetchWishlist}
                                                onRemove={handleRemoveFromWishlist}
                                            />
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </section>
                </main>

                <footer className="border-t py-8">
                    <div className="mx-auto max-w-6xl px-4 text-center text-sm text-muted-foreground sm:px-6">
                        <p>&copy; {new Date().getFullYear()} Wishlist. All rights reserved.</p>
                    </div>
                </footer>
            </div>
        </>
    );
}
