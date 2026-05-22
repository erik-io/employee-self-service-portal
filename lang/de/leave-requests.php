<?php

declare(strict_types=1);

return [
    // Page titles
    'manage' => 'Abwesenheitsanträge verwalten',
    'manage_subtitle' => 'Ausstehende Urlaubsanträge prüfen und freigeben',
    'my_requests' => 'Meine Abwesenheitsanträge',
    'history' => 'Verlauf der Abwesenheitsanträge',
    'create' => 'Neuen Urlaubsantrag',
    'details' => 'Details zum Abwesenheitsantrag',
    'pending_title' => 'Offene Anträge',
    'pending_subtitle' => 'Offene Anträge :pending zu prüfen',
    'view_history' => 'Historie anzeigen',
    'view_pending' => 'Offene anzeigen',
    'history_title' => 'Abwesenheitsübersicht',
    'history_subtitle' => 'Alle Abwesenheitsanträge und ihren Status einsehen',
    'history_summary' => ':total gesamt, :pending zu prüfen',
    'history_empty_title' => 'Noch keine Abwesenheitsanträge',
    'history_empty_body' => 'Abwesenheitsanträge erscheinen hier nach der Einreichung.',

    // Status / overview
    'your_vacation_status' => 'Ihr Urlaubsstatus',
    'overview_current_year' => 'Übersicht Ihrer verbleibenden Urlaubstage im laufenden Jahr.',
    'days_remaining' => 'Verbleibende Tage',
    'submitted_on' => 'Eingereicht am',
    'reviewer' => 'Prüfer',
    'rejection_reason' => 'Ablehnungsgrund',
    'back_to_requests' => 'Zurück zu den Anträgen',
    'no_leave_requests_yet' => 'Sie haben noch keine Abwesenheitsanträge eingereicht.',

    // Form
    'note' => 'Hinweis:',
    'absence_type' => 'Abwesenheitsart',
    'start_date' => 'Startdatum',
    'end_date' => 'Enddatum',
    'submit_request' => 'Antrag einreichen',
    'cancel' => 'Abbrechen',

    // Table / management
    'employee' => 'Mitarbeiter',
    'type' => 'Art',
    'period' => 'Zeitraum',
    'status' => 'Status',
    'actions' => 'Aktionen',
    'review' => 'Prüfen',
    'view_details' => 'Details anzeigen',
    'no_leave_requests' => 'Keine Urlaubsanträge gefunden.',
    // Team capacity / occupancy
    'team_capacity_check' => 'Teamkapazitätsprüfung',
    'no_overlaps' => 'Keine Überschneidungen festgestellt. Das Team ist in diesem Zeitraum vollständig besetzt.',

    'modal' => [
        'approval' => [
            'title' => 'Genehmigung bestätigen',
            'body' => 'Bitte bestätigen Sie, dass Sie diesen Urlaubsantrag genehmigen möchten.',
            'confirm' => 'Ja, genehmigen',
        ],
        'rejection' => [
            'title' => 'Ablehnung bestätigen',
            'body' => 'Bitte prüfen Sie die Details des Antrags, bevor Sie ihn ablehnen.',
        ],
    ],

    'feedback' => [
        'approved' => 'Abwesenheitsantrag erfolgreich genehmigt.',
        'rejected' => 'Abwesenheitsantrag erfolgreich abgelehnt.',
    ],
    'pending_summary' => ':pending ausstehend / :total gesamt',
    'my_absences_title' => 'Meine Abwesenheiten',
    'my_absences_summary' => ':total gesamt',
    'overlap_vacation' => 'Der angefragte Zeitraum überschneidet sich mit einem bestehenden Urlaub von :start bis :end.',
];
