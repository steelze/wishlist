import { useState } from 'react';
import axios from 'axios';
import { Heart, Trash2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';

export interface WishlistItem {
    id: number;
    name: string;
    price: string;
}

interface WishlistProps {
    items: WishlistItem[];
    loading: boolean;
    error: string | null;
    onRetry: () => void;
    onRemove: (productId: number) => void;
}

function WishlistSkeleton() {
    return (
        <div className="flex items-center justify-between py-3 border-b last:border-0">
            <div className="space-y-2">
                <Skeleton className="h-4 w-32" />
                <Skeleton className="h-3 w-16" />
            </div>
            <Skeleton className="h-8 w-8" />
        </div>
    );
}

export default function Wishlist({ items, loading, error, onRetry, onRemove }: WishlistProps) {
    const [removing, setRemoving] = useState<number | null>(null);

    const handleRemove = async (productId: number) => {
        setRemoving(productId);
        try {
            await axios.delete(`/api/v1/wishlist/${productId}`);
            onRemove(productId);
        } catch {
            alert('Failed to remove item');
        } finally {
            setRemoving(null);
        }
    };

    let wishlistContent = null;

    if (loading) {
        wishlistContent = (
            <div className="space-y-1">
                {[...Array(3)].map((_, i) => (
                    <WishlistSkeleton key={i} />
                ))}
            </div>
        );
    } else if (error) {
        wishlistContent = (
            <div className="text-center py-4">
                <p className="text-destructive text-sm">{error}</p>
                <Button variant="outline" size="sm" className="mt-2" onClick={onRetry}>
                    Try again
                </Button>
            </div>
        );
    } else {
        wishlistContent = items.length === 0 ? (
            <p className="text-muted-foreground text-sm text-center py-4">
                Your wishlist is empty.
            </p>
        ) : (
            <div className="space-y-1">
                {items.map((item) => (
                    <div
                        key={item.id}
                        className="flex items-center justify-between py-3 border-b last:border-0"
                    >
                        <div>
                            <p className="font-medium text-sm line-clamp-1">{item.name}</p>
                            <p className="text-muted-foreground text-xs">${item.price}</p>
                        </div>
                        <Button
                            variant="ghost"
                            size="sm"
                            className="text-muted-foreground hover:text-destructive"
                            onClick={() => handleRemove(item.id)}
                            disabled={removing === item.id}
                        >
                            <Trash2 className="size-4" />
                            {removing === item.id ? 'Removing...' : 'Remove'}
                        </Button>
                    </div>
                ))}
            </div>
        );
    }

    return (
        <div>
            <h2 className="flex items-center text-2xl font-bold mb-6 text-foreground">
                <Heart className="size-6 text-primary mr-2" />
                <span>My Wishlist</span>
            </h2>
            <Card>
                <CardContent className="pt-6 max-h-[calc(100vh-12rem)] overflow-y-auto">
                    {wishlistContent}
                </CardContent>
            </Card>
        </div>
    );
}
