import { type ClassValue, clsx } from "clsx"
import { twMerge } from "tailwind-merge"

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

/**
 * Calculate the percentage of a price compared to the original price
 * @param price
 * @param originalPrice
 */
export function calculatePercentage(
  price: number,
  originalPrice: number
): string {
  if (originalPrice === 0) {
    return "Le prix original ne peut pas être 0"
  }
  const difference = originalPrice - price
  const percentage = (difference / originalPrice) * 100

  return percentage % 1 === 0 ? percentage + "%" : percentage.toFixed(2) + "%"
}
