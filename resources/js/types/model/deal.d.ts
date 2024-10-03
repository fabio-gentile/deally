import { CategoryDeal } from "@/types/model/category-deal"
import { User } from "@/types/model/user"

export interface Deal {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  title: string
  description: string
  slug: string
  original_price: number | null
  price: number | null
  expiration_date: string
  start_date: string
  promo_code: string | null
  deal_url: string
  user_id: number
  category_deal_id: number
  // relations
  category_deal: CategoryDeal
  user: User
}
