<div>
    <table class="table">
        <tr>
            <th><%t Genealogist.Name 'Name' %></th>
            <th><%t Genealogist.START_DATE 'Start Date' %></th>
            <th><%t Genealogist.END_DATE 'End Date' %></th>
        </tr>
        <% loop TwonNames %>
        <tr>
            <td>$Name</td>
            <td><% if StartDate %>$StartDate<% else %>--<% end_if %></td>
            <td><% if EndDate %>$EndDate<% else %>--<% end_if %></td>
        </tr>
        <% end_loop %>
    </table>
</div>
