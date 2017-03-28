<section class="tl-container <% if isFemale %>female<% end_if %>">
    <div class="tl-plain-block">
        <p>Test test test</p>
    </div>

    <% loop LifeEvents %>
    <div class="tl-block">
        <div class="tl-ball">
            <div class="tl-ball-content">
                <% if DatePrecision == 'Accurate' %>
                    <span class="tl-ball-text">$EventDate.Format(M d)</span>
                <% else_if DatePrecision == 'Estimated' %>
                    <span class="tl-ball-text"><%t Genealogist.EST 'Est.' %></span>
                <% else_if DatePrecision == 'Calculated' %>
                    <span class="tl-ball-text"><%t Genealogist.CALC 'Calc.' %></span>
                <% end_if %>

                <% if EventDate.Year %><span class="tl-ball-title">$EventDate.Year</span><% end_if %>
                <% if Age %><span class="tl-ball-text"><%t Genealogist.AGE 'Age' %> $Age</span><% end_if %>
            </div>
        </div>

        <div class="tl-content">
            <span class="tl-content-place"><span class="tl-content-date">$EventDate.Format(M d)</span><%if EventPlace %> <i class="fa fa-map-marker" aria-hidden="true"></i> $EventPlace<% end_if%></span>
            <span class="tl-content-title">$Title</span>
            <% if RelatedPerson  && RelatedPerson.ID != Person.ID %>
                <p class="tl-content-text"><a href="{$RelatedPerson.ShowLink}">$RelatedPerson.AliasSummary</a></p>
            <% end_if %>
            <div class="tl-content-text">$Content</div>
        </div>
    </div>
    <% end_loop %>
</section>