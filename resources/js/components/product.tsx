import { useEffect, useState } from 'react';
import axios from 'axios';
import { Heart, ShoppingBag } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';

interface Product {
    id: number;
    name: string;
    price: string;
    description: string;
}

interface ProductsProps {
    isAuthenticated?: boolean;
    onWishlistUpdate?: () => void;
}

export default function Products({ isAuthenticated, onWishlistUpdate }: ProductsProps) {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [addingToWishlist, setAddingToWishlist] = useState<number | null>(null);

    useEffect(() => {
        fetchProducts();
    }, []);

    const fetchProducts = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/api/v1/products');
            setProducts(response.data.data);
        } catch {
            setError('Failed to load products');
        } finally {
            setLoading(false);
        }
    };

    const addToWishlist = async (productId: number) => {
        if (!isAuthenticated) {
            alert('Please log in to add items to your wishlist');
            return;
        }

        setAddingToWishlist(productId);
        try {
            await axios.post(`/api/v1/wishlist/${productId}`);
            onWishlistUpdate?.();
        } catch (err: unknown) {
            const error = err as { response?: { status?: number } };
            if (error.response?.status === 409) {
                alert('Product already in wishlist');
            } else {
                alert('Something went wrong');
            }
        } finally {
            setAddingToWishlist(null);
        }
    };

    let productsContent = null;

    if (loading) {
        productsContent = <ProductSkeleton />;
    } else if (error) {
        productsContent = (
            <div className="w-full max-w-6xl mx-auto">
                <div className="rounded-lg border border-destructive/50 bg-destructive/10 p-6 text-center">
                    <p className="text-destructive">{error}</p>
                    <Button variant="outline" className="mt-4" onClick={fetchProducts}>
                        Try again
                    </Button>
                </div>
            </div>
        );
    } else {
        productsContent = (products.length === 0 ? (
            <div className="rounded-lg border border-muted bg-muted/50 p-12 text-center">
                <p className="text-muted-foreground">No products available yet.</p>
            </div>
        ) : (
            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {products.map((product) => (
                    <Card key={product.id} className="flex flex-col">
                        <CardHeader>
                            <CardTitle className="line-clamp-1">{product.name}</CardTitle>
                        </CardHeader>
                        <CardContent className="flex-1">
                            <p className="text-muted-foreground text-sm line-clamp-3 mb-3">
                                {product.description}
                            </p>
                            <p className="text-lg font-semibold text-foreground">
                                ${product.price}
                            </p>
                        </CardContent>
                        <CardFooter>
                            <Button
                                className="w-full"
                                variant="outline"
                                onClick={() => addToWishlist(product.id)}
                                disabled={addingToWishlist === product.id}
                            >
                                <Heart className="size-4" />
                                {addingToWishlist === product.id ? 'Adding...' : 'Add to Wishlist'}
                            </Button>
                        </CardFooter>
                    </Card>
                ))}
            </div>
        ))
    }

    return (
        <div className="w-full max-w-6xl mx-auto">
            <h2 className="flex items-center text-2xl font-bold mb-6 text-foreground">
                <ShoppingBag className="size-6 text-primary mr-2" />
                <span>All Products</span>
            </h2>

            {productsContent}
        </div>
    );
}

function ProductSkeleton() {
    return (
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {[...Array(9)].map((_, i) => (
                <Card  key={i}>
                    <CardHeader>
                        <Skeleton className="h-6 w-3/4" />
                    </CardHeader>
                    <CardContent className="space-y-2">
                        <Skeleton className="h-4 w-full" />
                        <Skeleton className="h-4 w-2/3" />
                        <Skeleton className="h-5 w-20 mt-2" />
                    </CardContent>
                    <CardFooter>
                        <Skeleton className="w-full" />
                    </CardFooter>
                </Card>
            ))}
        </div>
    );
}
