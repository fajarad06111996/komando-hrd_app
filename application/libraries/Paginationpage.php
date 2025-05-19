<?php
if (!defined("BASEPATH")) exit("No direct script access allowed");

class paginationpage
{
    function pagination_html($total_result, $limit_per_page, $pagination_active, $url_source, $panel_id, $keyword)
    {
        if($total_result <= $limit_per_page) {
            $pagination = '<div class="pagination mb-3">
                <a href="javascript:void(0)" class="active">1</a>
            </div>';
        }
        else {
            $total_pagination = ceil($total_result / $limit_per_page);
            //Button prev ******************************
            if($pagination_active > 1) {
                $prev = '<a href="javascript:void(0)" onclick="getNumberPage(`1`, `' . $url_source . '`, `' . $panel_id . '`, `' . $keyword . '`)"><i class="fas fa-angle-double-left"></i></a>
                    <a href="javascript:void(0)" onclick="getNumberPage(`' . ($pagination_active - 1) . '`, `' . $url_source . '`, `' . $panel_id . '`, `' . $keyword . '`)"><i class="fas fa-angle-left"></i></a>';
            }
            else { $prev = ''; }
            //Button other prev ******************************
            if(($pagination_active - 1) > 1) { $other_prev = '<a href="javascript:void(0)"><i class="fas fa-ellipsis-h"></i></a>'; }
            else { $other_prev = ''; }
            //Button before pagination active ******************************
            if(($pagination_active - 1) >= 1) { $number_before = '<a href="javascript:void(0)" onclick="getNumberPage(`' . ($pagination_active - 1) . '`, `' . $url_source . '`, `' . $panel_id . '`, `' . $keyword . '`)">' . ($pagination_active - 1) . '</a>'; }
            else { $number_before = ''; }
            
            //Button next ******************************
            if($pagination_active < $total_pagination) {
                $next = '<a href="javascript:void(0)" onclick="getNumberPage(`' . ($pagination_active + 1) . '`, `' . $url_source . '`, `' . $panel_id . '`, `' . $keyword . '`)"><i class="fas fa-angle-right"></i></a>
                <a href="javascript:void(0)" onclick="getNumberPage(`' . $total_pagination . '`, `' . $url_source . '`, `' . $panel_id . '`, `' . $keyword . '`)"><i class="fas fa-angle-double-right"></i></a>';
            }
            else { $next = ''; }
            //Button other next ******************************
            if($total_pagination > 5) { 
                if(($pagination_active + 2) < $total_pagination) { $other_next = '<a href="javascript:void(0)"><i class="fas fa-ellipsis-h"></i></a>'; }
                else { $other_next = ''; }
            }
            else { $other_next = ''; }
           //Button after pagination active ******************************
            if($pagination_active >= $total_pagination) { $number_after = ''; }
            else { $number_after = '<a href="javascript:void(0)" onclick="getNumberPage(`' . ($pagination_active + 1) . '`, `' . $url_source . '`, `' . $panel_id . '`, `' . $keyword . '`)">' . ($pagination_active + 1) . '</a>'; }
            //Button after pagination active last ******************************
            if(($pagination_active + 2) > $total_pagination) { $number_last = ''; }
            else { $number_last = '<a href="javascript:void(0)" onclick="getNumberPage(`' . ($pagination_active + 2) . '`, `' . $url_source . '`, `' . $panel_id . '`, `' . $keyword . '`)">' . ($pagination_active + 2) . '</a>'; }
            
            $pagination = '<div class="table-responsive">
                <div class="pagination mb-3">
                    ' . $prev . '
                    ' . $other_prev . '
                    ' . $number_before . '
                    <a href="javascript:void(0)" class="active">' . $pagination_active . '</a>
                    ' . $number_after . '
                    ' . $number_last . '
                    ' . $other_next . '
                    ' . $next . '
                </div>
            </div>';
        }
        
        return $pagination;
    }
}