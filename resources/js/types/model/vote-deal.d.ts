import { Deal } from "@/types/model/deal"
import { User } from "@/types/model/user"

export interface VoteDeals {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  deal_id: number
  user_id: number
  type: "up" | "down"
  // relations
  deal: Deal
  user: User
}
